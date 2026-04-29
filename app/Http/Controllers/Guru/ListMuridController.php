<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PesanGuruSiswa;   // ← pakai model PesanGuruSiswa (tabel pesan_guru_siswa)
use App\Models\KebiasaanHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ListMuridController extends Controller
{
    // ── Halaman list murid ──────────────────────────────────────────────────

    public function index(): View
    {
        $user   = Auth::user();
        $guruId = $this->getGuruId();

        // Ambil siswa wali kelas tanpa filter NISN
        $siswaList = User::where('guru_wali_id', $guruId)
            ->orderBy('name')
            ->get();

        // Fallback: coba lewat relasi tabel guru
        if ($siswaList->isEmpty()) {
            $guru = $user->guru ?? null;
            if ($guru) {
                $siswaList = User::where('guru_wali_id', $guru->id)
                            ->orderBy('name')
                    ->get();
            }
        }

        return view('guru.list-murid', compact('user', 'siswaList'));
    }

    // ── AJAX: ambil daftar siswa + progress ─────────────────────────────────

    public function getSiswa(Request $request): JsonResponse
    {
        $user   = Auth::user();
        $guruId = $this->getGuruId();

        $periode = $request->input('periode', '');
        $filter  = $request->input('filter', '');

        // Ambil semua siswa wali kelas
        $siswaList = User::where('guru_wali_id', $guruId)
            ->with('kelas')
            ->orderBy('name')
            ->get();

        // Jika tidak ada periode, tampilkan semua siswa dengan penyelesaian form hari ini
        if (empty($periode)) {
            $data = $siswaList->map(function ($siswa) use ($guruId) {
                // Progress kebiasaan hari ini
                $today = now()->toDateString();
                $kebiasaanHariIni = KebiasaanHarian::where('user_id', $siswa->id)
                    ->whereDate('tanggal', $today)
                    ->first();

                // Hitung persentase penyelesaian form hari ini (max 7 kebiasaan)
                $totalSelesai = $kebiasaanHariIni ? $kebiasaanHariIni->hitungSelesai() : 0;
                $persen = (int) round(($totalSelesai / 7) * 100);

                return [
                    'id'             => $siswa->id,
                    'nama'           => $siswa->name,
                    'nisn'           => $siswa->nisn ?? '-',
                    'kelas'          => $siswa->kelas?->nama_kelas ?? '-',
                    'tanggal_lahir'  => $siswa->birth_date
                        ? Carbon::parse($siswa->birth_date)->format('d - m - Y') : '-',
                    'persen'         => $persen,
                    'detail'         => null,
                    'umpan_balik'    => null,
                    'has_umpan_balik'=> false,
                    'mode_no_periode'=> true, // flag untuk tampilan tanpa periode
                ];
            })->sortByDesc('persen')->values();

            return response()->json([
                'success'  => true,
                'data'     => $data,
                'total'    => $data->count(),
                'no_periode' => true,
                '_debug'   => [
                    'guru_user_id'    => $guruId,
                    'total_siswa'     => $siswaList->count(),
                ],
            ]);
        }

        // Jika ada periode tapi tidak ada filter
        if (! $filter) {
            return response()->json(['success' => false, 'message' => 'Filter wajib diisi untuk periode ' . $periode . '.']);
        }

        [$mulai, $selesai] = $this->rentangTanggal($periode, $filter);

        $data = $siswaList->map(function ($siswa) use ($guruId, $periode, $filter, $mulai, $selesai) {

            // Progress kebiasaan
            $kebiasaanList = KebiasaanHarian::where('user_id', $siswa->id)
                ->whereBetween('tanggal', [$mulai, $selesai])
                ->get();

            $totalHari    = Carbon::parse($mulai)->diffInDays(Carbon::parse($selesai)) + 1;
            $totalSelesai = $kebiasaanList->sum(fn ($k) => $k->hitungSelesai());
            $maxPossible  = $totalHari * 7;
            $persen       = $maxPossible > 0 ? (int) round($totalSelesai / $maxPossible * 100) : 0;

            $aspekMap = [
                'bangun_pagi'   => 'bangun_pagi',
                'beribadah'     => 'baca_quran',
                'berolahraga'   => 'berolahraga',
                'makan_sehat'   => 'makan_sehat',
                'gemar_belajar' => 'gemar_belajar',
                'bermasyarakat' => 'bersama',
                'tidur_cepat'   => 'tidur_cepat',
            ];

            $detail = [];
            foreach ($aspekMap as $label => $field) {
                $hariIsi        = $kebiasaanList->filter(fn ($k) => ! is_null($k->$field))->count();
                $detail[$label] = $totalHari > 0 ? (int) round($hariIsi / $totalHari * 100) : 0;
            }

            // Umpan balik: filter berdasarkan siswa_id + periode + kolom periode spesifik
            $pesanQuery = PesanGuruSiswa::where('siswa_id', $siswa->id)
                ->where('periode', $periode);
            $this->filterPesanByPeriode($pesanQuery, $periode, $filter);
            $pesan = $pesanQuery->latest()->first();

            // Get detailed kebiasaan data for harian period
            $kebiasaanData = null;
            if ($periode === 'harian') {
                $kebiasaanData = KebiasaanHarian::where('user_id', $siswa->id)
                    ->whereDate('tanggal', $filter)
                    ->first();
                
                if ($kebiasaanData) {
                    // Build sholat list with times
                    $sholatList = [];
                    if ($kebiasaanData->sholat_subuh) {
                        $sholatList[] = [
                            'nama' => 'Subuh',
                            'jam' => $this->formatTime($kebiasaanData->jam_sholat_subuh),
                        ];
                    }
                    if ($kebiasaanData->sholat_dzuhur) {
                        $sholatList[] = [
                            'nama' => 'Dzuhur',
                            'jam' => $this->formatTime($kebiasaanData->jam_sholat_dzuhur),
                        ];
                    }
                    if ($kebiasaanData->sholat_ashar) {
                        $sholatList[] = [
                            'nama' => 'Ashar',
                            'jam' => $this->formatTime($kebiasaanData->jam_sholat_ashar),
                        ];
                    }
                    if ($kebiasaanData->sholat_maghrib) {
                        $sholatList[] = [
                            'nama' => 'Maghrib',
                            'jam' => $this->formatTime($kebiasaanData->jam_sholat_maghrib),
                        ];
                    }
                    if ($kebiasaanData->sholat_isya) {
                        $sholatList[] = [
                            'nama' => 'Isya',
                            'jam' => $this->formatTime($kebiasaanData->jam_sholat_isya),
                        ];
                    }
                    
                    // Parse olahraga JSON data
                    $olahragaList = [];
                    if (is_array($kebiasaanData->jenis_olahraga)) {
                        foreach ($kebiasaanData->jenis_olahraga as $item) {
                            if (isset($item['jenis'])) {
                                $olahragaList[] = [
                                    'jenis' => $item['jenis'],
                                    'catatan' => $item['catatan'] ?? null,
                                ];
                            }
                        }
                    }
                    
                    // Parse bermasyarakat JSON array
                    $masyarakatList = [];
                    if (is_array($kebiasaanData->bersama)) {
                        foreach ($kebiasaanData->bersama as $item) {
                            if (is_string($item)) {
                                $masyarakatList[] = $item;
                            }
                        }
                    }
                    $masyarakatString = !empty($masyarakatList) ? implode(', ', $masyarakatList) : '-';
                    
                    $kebiasaanData = [
                        'bangun_pagi' => $kebiasaanData->bangun_pagi,
                        'bangun_pagi_jam' => $this->formatTime($kebiasaanData->jam_bangun),
                        'bangun_pagi_catatan' => $kebiasaanData->bangun_catatan ?: '-',
                        'beribadah_sholat_list' => $sholatList,
                        'beribadah_quran' => $kebiasaanData->baca_quran,
                        'beribadah_surah' => $this->getSurahName($kebiasaanData->quran_surah),
                        'beribadah_catatan' => $kebiasaanData->ibadah_catatan ?: '-',
                        'berolahraga' => $kebiasaanData->berolahraga,
                        'berolahraga_list' => $olahragaList,
                        'berolahraga_catatan' => $kebiasaanData->olahraga_catatan ?: '-',
                        'makan_sehat' => $kebiasaanData->makan_sehat,
                        'makan_pagi' => $kebiasaanData->makan_pagi ?: '-',
                        'makan_siang' => $kebiasaanData->makan_siang ?: '-',
                        'makan_malam' => $kebiasaanData->makan_malam ?: '-',
                        'makan_catatan' => $kebiasaanData->makan_catatan ?: '-',
                        'gemar_belajar' => $kebiasaanData->gemar_belajar,
                        'gemar_belajar_jenis' => $kebiasaanData->materi_belajar ?: '-',
                        'gemar_belajar_catatan' => $kebiasaanData->belajar_catatan ?: '-',
                        'bermasyarakat_dengan' => $masyarakatString,
                        'bermasyarakat_catatan' => $kebiasaanData->masyarakat_catatan ?: '-',
                        'tidur_cepat' => $kebiasaanData->tidur_cepat,
                        'tidur_cepat_jam' => $this->formatTime($kebiasaanData->jam_tidur),
                        'tidur_cepat_catatan' => $kebiasaanData->tidur_catatan ?: '-',
                    ];
                }
            }

            return [
                'id'             => $siswa->id,
                'nama'           => $siswa->name,
                'nisn'           => $siswa->nisn ?? '-',
                'kelas'          => $siswa->kelas?->nama_kelas ?? '-',
                'tanggal_lahir'  => $siswa->birth_date
                    ? Carbon::parse($siswa->birth_date)->format('d - m - Y') : '-',
                'persen'         => $persen,
                'detail'         => $detail,
                'umpan_balik'    => $pesan?->isi,
                'umpan_balik_id' => $pesan?->id,
                'has_umpan_balik'=> $pesan !== null,
                'mode_no_periode'=> false,
                'kebiasaan'      => $kebiasaanData,
            ];
        })->sortByDesc('persen')->values();

        return response()->json([
            'success'  => true,
            'data'     => $data,
            'total'    => $data->count(),
            'rentang'  => ['mulai' => $mulai, 'selesai' => $selesai],
            '_debug'   => [
                'guru_user_id'    => $guruId,
                'total_siswa'     => $siswaList->count(),
            ],
        ]);
    }

    // ── AJAX: history pesan guru → siswa ────────────────────────────────────

    public function getPesanHistory(Request $request, int $siswaId): JsonResponse
    {

        if (! $siswaId) {
            return response()->json(['success' => false, 'message' => 'siswa_id wajib diisi.']);
        }

        $siswa = User::find($siswaId);
        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
        }

        // Ambil SEMUA pesan untuk siswa ini tanpa filter guru_id
        // karena guru_id bisa merujuk ke users.id atau guru.id tergantung konfigurasi
        $pesanList = PesanGuruSiswa::where('siswa_id', $siswaId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($p) => [
                'id'         => $p->id,
                'nama_siswa' => $siswa->name,
                'judul'      => $p->judul,
                'isi'        => $p->isi,
                'tanggal'    => $p->created_at->locale('id')->translatedFormat('d F Y'),
            ]);

        return response()->json([
            'success' => true,
            'data'    => $pesanList,
        ]);
    }

    // ── AJAX: kirim pesan guru → siswa ──────────────────────────────────────

    public function kirimPesan(Request $request): JsonResponse
    {
        $user   = Auth::user();
        $guruId = $this->getGuruId();

        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:users,id'],
            'judul'    => ['required', 'string', 'max:255'],
            'isi'      => ['required', 'string', 'max:2000'],
            'periode'  => ['required', 'string', 'in:harian,mingguan,pertemuan,bulanan'],
            'filter'   => ['nullable', 'string'],
        ]);

        $siswa = User::find($request->siswa_id);

        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
        }

        // guru_id harus dari tabel guru (FK: pesan_guru_siswa.guru_id → guru.id)
        $pesanData = [
            'guru_id'  => $this->getGuruId(),
            'siswa_id' => $request->siswa_id,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'periode'  => $request->periode,
        ];

        // Isi kolom periode spesifik sesuai struktur tabel pesan_guru_siswa
        $filter = $request->filter ?? '';
        switch ($request->periode) {
            case 'harian':
                $pesanData['tanggal'] = $filter ?: now()->toDateString();
                break;
            case 'mingguan':
                $pesanData['minggu'] = $filter;
                break;
            case 'pertemuan':
                $pesanData['pertemuan'] = $filter;
                break;
            case 'bulanan':
                if (str_contains($filter, '|')) {
                    [$bulan, $tahun] = explode('|', $filter);
                    $pesanData['bulan'] = (int) $bulan;
                    $pesanData['tahun'] = (int) $tahun;
                } else {
                    $pesanData['bulan'] = (int) $filter;
                    $pesanData['tahun'] = now()->year;
                }
                break;
        }

        PesanGuruSiswa::create($pesanData);

        return response()->json(['success' => true, 'message' => 'Pesan berhasil dikirim.']);
    }

    // ── AJAX: hapus umpan balik ─────────────────────────────────────────────

    public function hapusUmpanBalik(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:users,id'],
            'periode'  => ['nullable', 'string'],
            'filter'   => ['nullable', 'string'],
        ]);

        $siswaId = (int) $request->siswa_id;
        $periode = $request->input('periode', '');
        $filter  = $request->input('filter', '');

        // Jika ada umpan_balik_id spesifik → hapus 1 record itu saja
        if ($request->filled('umpan_balik_id')) {
            $deleted = PesanGuruSiswa::where('id', $request->umpan_balik_id)
                ->where('siswa_id', $siswaId)
                ->delete();

            if (! $deleted) {
                return response()->json(['success' => false, 'message' => 'Umpan balik tidak ditemukan.']);
            }

            return response()->json(['success' => true, 'message' => 'Umpan balik berhasil dihapus.']);
        }

        // Tidak ada umpan_balik_id → hapus semua pesan untuk siswa ini
        // pada periode + filter yang sedang aktif
        $query = PesanGuruSiswa::where('siswa_id', $siswaId);

        if ($periode) {
            $query->where('periode', $periode);
            $this->filterPesanByPeriode($query, $periode, $filter);
        }

        $deleted = $query->delete();

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Tidak ada umpan balik untuk dihapus.']);
        }

        return response()->json([
            'success' => true,
            'message' => $deleted . ' umpan balik berhasil dihapus.',
        ]);
    }

    // ────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Ambil guru_id dari tabel guru berdasarkan user yang login.
     * FK pesan_guru_siswa.guru_id → guru.id (bukan users.id)
     */
    private function getGuruId(): int
    {
        $user = Auth::user();
        // Coba lewat relasi $user->guru (model Guru)
        if ($user->guru) {
            return $user->guru->id;
        }
        // Fallback: cari di tabel guru berdasarkan user_id
        $guru = \App\Models\Guru::where('user_id', $user->id)->first();
        if ($guru) {
            return $guru->id;
        }
        // Last resort: pakai users.id (akan gagal FK jika tidak cocok)
        return $user->id;
    }


    /**
     * Hitung rentang tanggal dari periode + filter.
     */
    private function rentangTanggal(string $periode, string $filter): array
    {
        switch ($periode) {
            case 'harian':
                $tgl = Carbon::parse($filter);
                return [$tgl->toDateString(), $tgl->toDateString()];

            case 'mingguan':
                if (preg_match('/(\d{4})-(\d{2})-W(\d+)/', $filter, $m)) {
                    $tahun  = (int) $m[1];
                    $bulan  = (int) $m[2];
                    $minggu = (int) $m[3];
                    $mulai  = Carbon::create($tahun, $bulan, 1)->addWeeks($minggu - 1);
                    $akhir  = (clone $mulai)->addDays(6);
                    $akhirBulan = Carbon::create($tahun, $bulan)->endOfMonth();
                    if ($akhir->gt($akhirBulan)) $akhir = $akhirBulan;
                    return [$mulai->toDateString(), $akhir->toDateString()];
                }
                return [now()->toDateString(), now()->toDateString()];

            case 'pertemuan':
                if (preg_match('/(\d{4})-P(\d+)/', $filter, $m)) {
                    $tahun     = (int) $m[1];
                    $pertemuan = (int) $m[2];
                    $mulai     = Carbon::create($tahun, 1, 1)->addWeeks(($pertemuan - 1) * 2);
                    $akhir     = (clone $mulai)->addDays(13);
                    return [$mulai->toDateString(), $akhir->toDateString()];
                }
                return [now()->toDateString(), now()->toDateString()];

            case 'bulanan':
                if (str_contains($filter, '|')) {
                    [$bulan, $tahun] = explode('|', $filter);
                    $bulan = (int) $bulan;
                    $tahun = (int) $tahun;
                } else {
                    $bulan = (int) $filter;
                    $tahun = now()->year;
                }
                $mulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhir = (clone $mulai)->endOfMonth();
                return [$mulai->toDateString(), $akhir->toDateString()];

            default:
                return [now()->toDateString(), now()->toDateString()];
        }
    }

    /**
     * Filter query PesanGuruSiswa berdasarkan kolom periode yang sesuai.
     */
    private function filterPesanByPeriode($query, string $periode, string $filter): void
    {
        switch ($periode) {

            case 'harian':
                // filter = "2026-03-15" — cocok dengan kolom tanggal DATE
                $query->whereDate('tanggal', $filter);
                break;

            case 'mingguan':
                // filter = "2026-03-W2" — cocok dengan kolom minggu VARCHAR
                $query->where('minggu', $filter);
                break;

            case 'pertemuan':
                // filter = "2026-P3" — cocok dengan kolom pertemuan VARCHAR
                $query->where('pertemuan', $filter);
                break;

            case 'bulanan':
                // filter = "3|2026" atau "3" — cocok dengan kolom bulan + tahun INT
                if (str_contains($filter, '|')) {
                    [$bulan, $tahun] = explode('|', $filter);
                } else {
                    $bulan = $filter;
                    $tahun = now()->year;
                }
                $query->where('bulan', (int) $bulan)
                      ->where('tahun', (int) $tahun);
                break;
        }
    }

    /**
     * Format time to HH:MM format (remove seconds)
     */
    private function formatTime($time): string
    {
        if (is_null($time)) return '-';
        
        // If it's already a string, try to parse it
        if (is_string($time)) {
            // Check if it has seconds (HH:MM:SS)
            if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                return substr($time, 0, 5); // Return HH:MM
            }
            return $time;
        }
        
        // If it's a Carbon/DateTime object
        if ($time instanceof \DateTime) {
            return $time->format('H:i');
        }
        
        return '-';
    }

    /**
     * Get surah name from surah number
     */
    private function getSurahName($surahNumber): string
    {
        if (is_null($surahNumber)) return '-';
        
        $surahNames = [
            1 => "Al-Fatihah", 2 => "Al-Baqarah", 3 => "Ali Imran", 4 => "An-Nisa",
            5 => "Al-Maidah", 6 => "Al-Anam", 7 => "Al-Araf", 8 => "Al-Anfal",
            9 => "At-Taubah", 10 => "Yunus", 11 => "Hud", 12 => "Yusuf",
            13 => "Ar-Rad", 14 => "Ibrahim", 15 => "Al-Hijr", 16 => "An-Nahl",
            17 => "Al-Isra", 18 => "Al-Kahf", 19 => "Maryam", 20 => "Ta-Ha",
            21 => "Al-Anbiya", 22 => "Al-Hajj", 23 => "Al-Muminun", 24 => "An-Nur",
            25 => "Al-Furqan", 26 => "Asy-Syuara", 27 => "An-Naml", 28 => "Al-Qasas",
            29 => "Al-Ankabut", 30 => "Ar-Rum", 31 => "Luqman", 32 => "As-Sajdah",
            33 => "Al-Ahzab", 34 => "Saba", 35 => "Fatir", 36 => "Ya-Sin",
            37 => "As-Saffat", 38 => "Sad", 39 => "Az-Zumar", 40 => "Ghafir",
            41 => "Fussilat", 42 => "Asy-Syura", 43 => "Az-Zukhruf", 44 => "Ad-Dukhan",
            45 => "Al-Jasiyah", 46 => "Al-Ahqaf", 47 => "Muhammad", 48 => "Al-Fath",
            49 => "Al-Hujurat", 50 => "Qaf", 51 => "Az-Zariyat", 52 => "At-Tur",
            53 => "An-Najm", 54 => "Al-Qamar", 55 => "Ar-Rahman", 56 => "Al-Waqiah",
            57 => "Al-Hadid", 58 => "Al-Mujadilah", 59 => "Al-Hashr", 60 => "Al-Mumtahanah",
            61 => "As-Saff", 62 => "Al-Jumuah", 63 => "Al-Munafiqun", 64 => "At-Taghabun",
            65 => "At-Talaq", 66 => "At-Tahrim", 67 => "Al-Mulk", 68 => "Al-Qalam",
            69 => "Al-Haqqah", 70 => "Al-Maarij", 71 => "Nuh", 72 => "Al-Jinn",
            73 => "Al-Muzzammil", 74 => "Al-Muddassir", 75 => "Al-Qiyamah", 76 => "Al-Insan",
            77 => "Al-Mursalat", 78 => "An-Naba", 79 => "An-Nazi'at", 80 => "Abasa",
            81 => "At-Takwir", 82 => "Al-Infitar", 83 => "Al-Mutaffifin", 84 => "Al-Insyiqaq",
            85 => "Al-Buruj", 86 => "At-Tariq", 87 => "Al-Ala", 88 => "Al-Ghasyiyah",
            89 => "Al-Fajr", 90 => "Al-Balad", 91 => "Asy-Syams", 92 => "Al-Lail",
            93 => "Ad-Duha", 94 => "Asy-Syarh", 95 => "At-Tin", 96 => "Al-Alaq",
            97 => "Al-Qadr", 98 => "Al-Bayyinah", 99 => "Az-Zalzalah", 100 => "Al-Adiyat",
            101 => "Al-Qariah", 102 => "At-Takathur", 103 => "Al-Asr", 104 => "Al-Humazah",
            105 => "Al-Fil", 106 => "Quraish", 107 => "Al-Maun", 108 => "Al-Kautsar",
            109 => "Al-Kafirun", 110 => "An-Nasr", 111 => "Al-Lahab", 112 => "Al-Ikhlas",
            113 => "Al-Falaq", 114 => "An-Nas",
        ];
        
        return $surahNames[$surahNumber] ?? $surahNumber;
    }

    // ── AJAX: ambil detail profil siswa ───────────────────────────────────────

    public function getSiswaProfile(int $siswaId): JsonResponse
    {
        $guruId = $this->getGuruId();

        // Ambil siswa wali kelas dengan semua field yang diperlukan
        $siswa = User::where('id', $siswaId)
            ->where('guru_wali_id', $guruId)
            ->with('kelas')
            ->first();

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan atau bukan murid bimbingan Anda.']);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'               => $siswa->id,
                'nama'             => $siswa->name,
                'nisn'             => $siswa->nisn ?? '-',
                'gender'           => $siswa->gender ?? '-',
                'foto'             => $siswa->foto,
                'tempat_lahir'     => $siswa->tempat_lahir ?? '-',
                'tanggal_lahir'    => $siswa->birth_date
                    ? Carbon::parse($siswa->birth_date)->format('d - m - Y') : '-',
                'angkatan'         => $siswa->angkatan ?? '-',
                'kelas'            => $siswa->kelas?->nama_kelas ?? '-',
                'hobi'             => $siswa->hobi ?? '-',
                'cita_cita'        => $siswa->cita_cita ?? '-',
                'teman_terbaik'    => $siswa->teman_terbaik ?? '-',
                'makanan_kesukaan' => $siswa->makanan_kesukaan ?? '-',
                'warna_kesukaan'   => $siswa->warna_kesukaan ?? '-',
                'no_telepon'       => $siswa->no_telepon ?? '-',
                'no_ortu'          => $siswa->no_ortu ?? '-',
                'alamat'           => $siswa->alamat ?? '-',
                'latitude'         => $siswa->latitude,
                'longitude'        => $siswa->longitude,
            ],
        ]);
    }

    // ── AJAX: ambil statistik kebiasaan per minggu ─────────────────────────────

    public function getWeeklyStats(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:users,id'],
            'filter'   => ['required', 'string'], // Format: "2026-03-W2"
        ]);

        $guruId = $this->getGuruId();
        $siswa = User::where('id', $request->siswa_id)
            ->where('guru_wali_id', $guruId)
            ->first();

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan atau bukan murid bimbingan Anda.']);
        }

        // Parse filter untuk mendapatkan rentang tanggal mingguan
        $filter = $request->filter;
        [$mulai, $selesai] = $this->rentangTanggal('mingguan', $filter);

        // Hitung jumlah hari yang seharusnya ada dalam periode
        $mulaiCarbon = Carbon::parse($mulai);
        $selesaiCarbon = Carbon::parse($selesai);
        $totalHari = $mulaiCarbon->diffInDays($selesaiCarbon) + 1;

        // Ambil semua kebiasaan dalam rentang minggu tersebut
        $kebiasaanList = KebiasaanHarian::where('user_id', $siswa->id)
            ->whereBetween('tanggal', [$mulai, $selesai])
            ->get();

        // Buat array tanggal yang ada untuk pencarian cepat
        $tanggalYangAda = $kebiasaanList->pluck('tanggal')->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        // Buat array kebiasaan berdasarkan tanggal untuk akses cepat
        $kebiasaanByTanggal = [];
        foreach ($kebiasaanList as $k) {
            $key = $k->tanggal->format('Y-m-d');
            $kebiasaanByTanggal[$key] = $k;
        }

        // Hitung statistik untuk setiap kebiasaan
        $aspekMap = [
            'bangun_pagi'   => 'bangun_pagi',
            'beribadah'     => 'baca_quran',
            'berolahraga'   => 'berolahraga',
            'makan_sehat'   => 'makan_sehat',
            'gemar_belajar' => 'gemar_belajar',
            'bermasyarakat' => 'bersama',
            'tidur_cepat'   => 'tidur_cepat',
        ];

        $statistik = [];
        foreach ($aspekMap as $label => $field) {
            $ya = 0;
            $tidak = 0;
            $tidakMengisi = 0;

            // Iterasi semua tanggal dalam periode
            $currentDate = $mulaiCarbon->copy();
            while ($currentDate->lte($selesaiCarbon)) {
                $dateStr = $currentDate->format('Y-m-d');

                if (!in_array($dateStr, $tanggalYangAda)) {
                    // Tidak ada record untuk tanggal ini = tidak mengisi
                    $tidakMengisi++;
                } else {
                    // Ada record, cek nilainya
                    $kebiasaan = $kebiasaanByTanggal[$dateStr];
                    if (is_null($kebiasaan->$field)) {
                        $tidakMengisi++;
                    } elseif ($kebiasaan->$field === true || $kebiasaan->$field === 1) {
                        $ya++;
                    } else {
                        $tidak++;
                    }
                }

                $currentDate->addDay();
            }

            $statistik[$label] = [
                'ya' => $ya,
                'tidak' => $tidak,
                'tidak_mengisi' => $tidakMengisi,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => [
                    'nama' => $siswa->name,
                    'nisn' => $siswa->nisn ?? '-',
                ],
                'periode' => $filter,
                'rentang' => [
                    'mulai' => $mulai,
                    'selesai' => $selesai,
                ],
                'total_hari' => $totalHari,
                'statistik' => $statistik,
            ],
        ]);
    }

    // ── AJAX: ambil statistik kebiasaan per pertemuan ─────────────────────────

    public function getMeetingStats(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:users,id'],
            'filter'   => ['required', 'string'], // Format: "2026-P3"
        ]);

        $guruId = $this->getGuruId();
        $siswa = User::where('id', $request->siswa_id)
            ->where('guru_wali_id', $guruId)
            ->first();

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan atau bukan murid bimbingan Anda.']);
        }

        // Parse filter untuk mendapatkan rentang tanggal pertemuan
        $filter = $request->filter;
        [$mulai, $selesai] = $this->rentangTanggal('pertemuan', $filter);

        // Hitung jumlah hari yang seharusnya ada dalam periode
        $mulaiCarbon = Carbon::parse($mulai);
        $selesaiCarbon = Carbon::parse($selesai);
        $totalHari = $mulaiCarbon->diffInDays($selesaiCarbon) + 1;

        // Ambil semua kebiasaan dalam rentang pertemuan tersebut
        $kebiasaanList = KebiasaanHarian::where('user_id', $siswa->id)
            ->whereBetween('tanggal', [$mulai, $selesai])
            ->get();

        // Buat array tanggal yang ada untuk pencarian cepat
        $tanggalYangAda = $kebiasaanList->pluck('tanggal')->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        // Buat array kebiasaan berdasarkan tanggal untuk akses cepat
        $kebiasaanByTanggal = [];
        foreach ($kebiasaanList as $k) {
            $key = $k->tanggal->format('Y-m-d');
            $kebiasaanByTanggal[$key] = $k;
        }

        // Hitung statistik untuk setiap kebiasaan
        $aspekMap = [
            'bangun_pagi'   => 'bangun_pagi',
            'beribadah'     => 'baca_quran',
            'berolahraga'   => 'berolahraga',
            'makan_sehat'   => 'makan_sehat',
            'gemar_belajar' => 'gemar_belajar',
            'bermasyarakat' => 'bersama',
            'tidur_cepat'   => 'tidur_cepat',
        ];

        $statistik = [];
        foreach ($aspekMap as $label => $field) {
            $ya = 0;
            $tidak = 0;
            $tidakMengisi = 0;

            // Iterasi semua tanggal dalam periode
            $currentDate = $mulaiCarbon->copy();
            while ($currentDate->lte($selesaiCarbon)) {
                $dateStr = $currentDate->format('Y-m-d');

                if (!in_array($dateStr, $tanggalYangAda)) {
                    // Tidak ada record untuk tanggal ini = tidak mengisi
                    $tidakMengisi++;
                } else {
                    // Ada record, cek nilainya
                    $kebiasaan = $kebiasaanByTanggal[$dateStr];
                    if (is_null($kebiasaan->$field)) {
                        $tidakMengisi++;
                    } elseif ($kebiasaan->$field === true || $kebiasaan->$field === 1) {
                        $ya++;
                    } else {
                        $tidak++;
                    }
                }

                $currentDate->addDay();
            }

            $statistik[$label] = [
                'ya' => $ya,
                'tidak' => $tidak,
                'tidak_mengisi' => $tidakMengisi,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => [
                    'nama' => $siswa->name,
                    'nisn' => $siswa->nisn ?? '-',
                ],
                'periode' => $filter,
                'rentang' => [
                    'mulai' => $mulai,
                    'selesai' => $selesai,
                ],
                'total_hari' => $totalHari,
                'statistik' => $statistik,
            ],
        ]);
    }

    // ── AJAX: ambil statistik kebiasaan per bulan ─────────────────────────────

    public function getMonthlyStats(Request $request): JsonResponse
    {
        $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:users,id'],
            'filter'   => ['required', 'string'], // Format: "3|2026" atau "3"
        ]);

        $guruId = $this->getGuruId();
        $siswa = User::where('id', $request->siswa_id)
            ->where('guru_wali_id', $guruId)
            ->first();

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan atau bukan murid bimbingan Anda.']);
        }

        // Parse filter untuk mendapatkan rentang tanggal bulanan
        $filter = $request->filter;
        [$mulai, $selesai] = $this->rentangTanggal('bulanan', $filter);

        // Hitung jumlah hari yang seharusnya ada dalam periode
        $mulaiCarbon = Carbon::parse($mulai);
        $selesaiCarbon = Carbon::parse($selesai);
        $totalHari = $mulaiCarbon->diffInDays($selesaiCarbon) + 1;

        // Ambil semua kebiasaan dalam rentang bulan tersebut
        $kebiasaanList = KebiasaanHarian::where('user_id', $siswa->id)
            ->whereBetween('tanggal', [$mulai, $selesai])
            ->get();

        // Buat array tanggal yang ada untuk pencarian cepat
        $tanggalYangAda = $kebiasaanList->pluck('tanggal')->map(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        // Buat array kebiasaan berdasarkan tanggal untuk akses cepat
        $kebiasaanByTanggal = [];
        foreach ($kebiasaanList as $k) {
            $key = $k->tanggal->format('Y-m-d');
            $kebiasaanByTanggal[$key] = $k;
        }

        // Hitung statistik untuk setiap kebiasaan
        $aspekMap = [
            'bangun_pagi'   => 'bangun_pagi',
            'beribadah'     => 'baca_quran',
            'berolahraga'   => 'berolahraga',
            'makan_sehat'   => 'makan_sehat',
            'gemar_belajar' => 'gemar_belajar',
            'bermasyarakat' => 'bersama',
            'tidur_cepat'   => 'tidur_cepat',
        ];

        $statistik = [];
        foreach ($aspekMap as $label => $field) {
            $ya = 0;
            $tidak = 0;
            $tidakMengisi = 0;

            // Iterasi semua tanggal dalam periode
            $currentDate = $mulaiCarbon->copy();
            while ($currentDate->lte($selesaiCarbon)) {
                $dateStr = $currentDate->format('Y-m-d');

                if (!in_array($dateStr, $tanggalYangAda)) {
                    // Tidak ada record untuk tanggal ini = tidak mengisi
                    $tidakMengisi++;
                } else {
                    // Ada record, cek nilainya
                    $kebiasaan = $kebiasaanByTanggal[$dateStr];
                    if (is_null($kebiasaan->$field)) {
                        $tidakMengisi++;
                    } elseif ($kebiasaan->$field === true || $kebiasaan->$field === 1) {
                        $ya++;
                    } else {
                        $tidak++;
                    }
                }

                $currentDate->addDay();
            }

            $statistik[$label] = [
                'ya' => $ya,
                'tidak' => $tidak,
                'tidak_mengisi' => $tidakMengisi,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => [
                    'nama' => $siswa->name,
                    'nisn' => $siswa->nisn ?? '-',
                ],
                'periode' => $filter,
                'rentang' => [
                    'mulai' => $mulai,
                    'selesai' => $selesai,
                ],
                'total_hari' => $totalHari,
                'statistik' => $statistik,
            ],
        ]);
    }

}