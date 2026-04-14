<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPertemuan;
use App\Models\AbsensiSiswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    // ── Halaman absensi ─────────────────────────────────────────────────────

    public function index(): View
    {
        $user   = Auth::user();
        $guruId = $this->getGuruId();

        $pertemuanInfo = $this->hitungPertemuan(now());

        $siswaList = $this->getSiswaWaliKelas();

        $absensiList = AbsensiSiswa::where('pertemuan_ke', $pertemuanInfo['ke'])
            ->where('tanggal_mulai', $pertemuanInfo['tanggal_mulai'])
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        $tidakAdaPertemuan = $absensiList->where('status', 'libur')->isNotEmpty();

        $fotoPertemuan = AbsensiPertemuan::where('guru_id', $guruId)
            ->where('pertemuan_ke', $pertemuanInfo['ke'])
            ->where('tanggal_mulai', $pertemuanInfo['tanggal_mulai'])
            ->first();

        return view('guru.absensi-murid', compact(
            'user',
            'siswaList',
            'absensiList',
            'pertemuanInfo',
            'tidakAdaPertemuan',
            'fotoPertemuan'
        ));
    }

    // ── AJAX: simpan absensi ────────────────────────────────────────────────

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'absensi'             => ['required', 'array'],
            'absensi.*.siswa_id'  => ['required', 'integer', 'exists:users,id'],
            'absensi.*.status'    => ['required', 'in:hadir,sakit,izin,tidak_hadir'],
            'pertemuan_ke'        => ['required', 'integer'],
            'tanggal_mulai'       => ['required', 'date'],
            'tanggal_selesai'     => ['required', 'date'],
        ]);

        $guruId = $this->getGuruId();

        foreach ($request->absensi as $item) {
            AbsensiSiswa::updateOrCreate(
                [
                    'siswa_id'      => $item['siswa_id'],
                    'pertemuan_ke'  => $request->pertemuan_ke,
                    'tanggal_mulai' => $request->tanggal_mulai,
                ],
                [
                    'guru_id'             => $guruId,
                    'tanggal_selesai'     => $request->tanggal_selesai,
                    'tanggal_absen'       => now()->toDateString(),
                    'status'              => $item['status']
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Absensi berhasil disimpan.']);
    }

    // ── AJAX: tandai tidak ada pertemuan (libur) ────────────────────────────

    public function tidakAdaPertemuan(Request $request): JsonResponse
    {
        $request->validate([
            'pertemuan_ke'    => ['required', 'integer'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
        ]);

        $guruId    = $this->getGuruId();
        $siswaList = $this->getSiswaWaliKelas();

        foreach ($siswaList as $siswa) {
            AbsensiSiswa::updateOrCreate(
                [
                    'siswa_id'      => $siswa->id,
                    'pertemuan_ke'  => $request->pertemuan_ke,
                    'tanggal_mulai' => $request->tanggal_mulai,
                ],
                [
                    'guru_id'             => $guruId,
                    'tanggal_selesai'     => $request->tanggal_selesai,
                    'tanggal_absen'       => null,
                    'status'              => 'libur'
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Pertemuan ditandai libur.']);
    }

    // ── AJAX: ambil data absensi pertemuan tertentu ─────────────────────────

    public function getByPertemuan(Request $request): JsonResponse
    {
        $pertemuanKe = (int) $request->query('pertemuan_ke');
        $tahun       = (int) $request->query('tahun', now()->year);

        if (! $pertemuanKe) {
            return response()->json(['success' => false, 'message' => 'pertemuan_ke wajib diisi.']);
        }

        $pertemuanInfo = $this->hitungPertemuanByKe($pertemuanKe, $tahun);

        $siswaList = $this->getSiswaWaliKelas();

        $absensiList = AbsensiSiswa::where('pertemuan_ke', $pertemuanKe)
            ->where('tanggal_mulai', $pertemuanInfo['tanggal_mulai'])
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        $data = $siswaList->map(fn($s) => [
            'siswa_id' => $s->id,
            'nama'     => $s->name,
            'nisn'     => $s->nisn ?? '-',
            'kelas'    => $s->kelas ?? '-',
            'status'   => $absensiList[$s->id]->status ?? 'tidak_hadir',
        ]);

        return response()->json([
            'success'             => true,
            'data'                => $data,
            'pertemuan'           => $pertemuanInfo,
        ]);
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'pertemuan_ke' => 'required|integer',
            'tanggal_mulai' => 'required|date',
        ]);

        $guruId = $this->getGuruId();

        $path = $request->file('foto')->store('dokumentasi', 'public');

        $old = AbsensiPertemuan::where([
            'guru_id' => $guruId,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tanggal_mulai' => $request->tanggal_mulai,
        ])->first();

        if ($old && $old->foto_dokumentasi) {
            Storage::disk('public')->delete($old->foto_dokumentasi);
        }

        AbsensiPertemuan::updateOrCreate(
            [
                'guru_id' => $guruId,
                'pertemuan_ke' => $request->pertemuan_ke,
                'tanggal_mulai' => $request->tanggal_mulai,
            ],
            [
                'tanggal_selesai' => $request->tanggal_selesai ?? now(),
                'foto_dokumentasi' => $path,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function batalkanLibur(Request $request): JsonResponse
    {
        $request->validate([
            'pertemuan_ke'    => ['required', 'integer'],
            'tanggal_mulai'   => ['required', 'date'],
        ]);

        $guruId    = $this->getGuruId();
        $siswaList = $this->getSiswaWaliKelas();

        foreach ($siswaList as $siswa) {
            AbsensiSiswa::where([
                'siswa_id'      => $siswa->id,
                'pertemuan_ke'  => $request->pertemuan_ke,
                'tanggal_mulai' => $request->tanggal_mulai,
                'guru_id'       => $guruId,
            ])->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Libur dibatalkan'
        ]);
    }
    // ────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────────────────────────────────

    /**
     * Ambil guru.id dari tabel guru (FK di absensi_siswa.guru_id → guru.id)
     */
    private function getGuruId(): int
    {
        $user = Auth::user();
        if ($user->guru) return $user->guru->id;
        $guru = \App\Models\Guru::where('user_id', $user->id)->first();
        return $guru ? $guru->id : $user->id;
    }

    /**
     * Ambil daftar siswa yang guru walinya adalah user yang login.
     * Mencoba semua kemungkinan nilai guru_wali_id di tabel users:
     *   1. guru.id  (dari tabel guru via relasi)
     *   2. users.id (jika guru_wali_id langsung pakai users.id)
     */
    private function getSiswaWaliKelas(): \Illuminate\Database\Eloquent\Collection
    {
        $user   = Auth::user();
        $guruId = $this->getGuruId(); // guru.id

        // Coba dengan guru.id dulu
        $siswa = User::where('guru_wali_id', $guruId)
            ->orderBy('name')
            ->get();

        // Jika kosong, coba dengan users.id sebagai fallback
        if ($siswa->isEmpty()) {
            $siswa = User::where('guru_wali_id', $user->id)
                ->orderBy('name')
                ->get();
        }

        return $siswa;
    }

    private function hitungPertemuan(Carbon $tanggal): array
    {
        $tahun = $tanggal->year;

        // Cari Sabtu pertama di tahun ini
        $sabtuPertama = Carbon::create($tahun, 1, 1)->next(\Carbon\CarbonInterface::SATURDAY);
        // Hitung jumlah minggu dari Sabtu pertama ke tanggal yang diberikan
        $mingguKe = $sabtuPertama->diffInWeeks($tanggal) + 1;

        // Setiap pertemuan = 2 minggu (2 Sabtu)
        $pertemuanKe = (int) ceil($mingguKe / 2);

        return $this->hitungPertemuanByKe($pertemuanKe, $tahun);
    }

    private function hitungPertemuanByKe(int $ke, int $tahun): array
    {
        $bulanId = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        // Cari Sabtu pertama di tahun ini
        $sabtuPertama = Carbon::create($tahun, 1, 1)->next(\Carbon\CarbonInterface::SATURDAY);
        // Hitung Sabtu pertama untuk pertemuan ini
        $sabtuPertamaPertemuan = (clone $sabtuPertama)->addWeeks(($ke - 1) * 2);

        // Sabtu kedua untuk pertemuan ini
        $sabtuKeduaPertemuan = (clone $sabtuPertamaPertemuan)->addWeek();

        return [
            'ke'              => $ke,
            'tahun'           => $tahun,
            'tanggal_mulai'   => $sabtuPertamaPertemuan->toDateString(),
            'tanggal_selesai' => $sabtuKeduaPertemuan->toDateString(),
            'label'           => 'Pertemuan ' . $ke . ' - ' .
                $sabtuPertamaPertemuan->day . ' & ' . $sabtuKeduaPertemuan->day . ' ' .
                $bulanId[$sabtuPertamaPertemuan->month - 1] . ' ' . $tahun,
        ];
    }
}
