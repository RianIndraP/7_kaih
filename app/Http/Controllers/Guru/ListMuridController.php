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

}