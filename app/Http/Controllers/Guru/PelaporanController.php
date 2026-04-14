<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPertemuan;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\KebiasaanHarian;
use App\Models\LampiranA;
use App\Models\LampiranB;
use App\Models\LampiranC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PelaporanController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', Auth::id())->first();

        $muridList = User::with('kelas')
            ->where('guru_wali_id', $guru->id)
            ->where('is_alumni', 0)
            ->get();

        $now = now();
        $tahunAwal = ($now->month >= 7) ? $now->year : $now->year - 1;
        $tahunAjaran = $tahunAwal . '/' . ($tahunAwal + 1);

        $catatanA = LampiranA::where('guru_id', $guru->id)
            ->where('tahun_ajaran', $tahunAjaran)
            ->pluck('catatan', 'murid_id');
        $catatanB = LampiranB::where('guru_id', $guru->id)
            ->where('bulan', request('bulan', now()->month))
            ->where('tahun', request('tahun', now()->year))
            ->get()
            ->groupBy(['murid_id', 'aspek']);

        $pertemuanData = AbsensiSiswa::where('guru_id', $guru->id)
            ->select('pertemuan_ke', 'tanggal_mulai')
            ->distinct()
            ->orderBy('pertemuan_ke', 'asc')
            ->get();
        $pertemuanList = $pertemuanData->map(function ($item) {
            return 'Pertemuan ' . $item->pertemuan_ke . ' - ' . Carbon::parse($item->tanggal_mulai)->format('d-m-Y');
        })->toArray();

        if (empty($pertemuanList)) {
            $pertemuanList = ['Pertemuan 1 - '];
        }

        foreach ($muridList as $m) {
            foreach (['akademik', 'karakter', 'sosial-emosional', 'kedisiplinan', 'potensi_minat'] as $aspek) {

                // ambil data kalau ada
                $data = $catatanB->get($m->id)?->get($aspek);

                if (!$data) {

                    $nilai = $this->hitungNilaiDariKebiasaan($m->id, $aspek);

                    $catatanB
                        ->put($m->id, $catatanB->get($m->id, collect()))
                        ->get($m->id)
                        ->put($aspek, collect([
                            (object)[
                                'deskripsi' => $this->getPredikat($nilai),
                                'tindak_lanjut' => $this->getTindak($nilai),
                                'keterangan' => ''
                            ]
                        ]));
                }
            }
        }

        $pertemuan = AbsensiSiswa::where('guru_id', $guru->id)
            ->where('pertemuan_ke', request('pertemuan', 1))
            ->select('tanggal_mulai', 'tanggal_selesai')
            ->first();

        $absensi = AbsensiSiswa::where('guru_id', $guru->id)
            ->where('pertemuan_ke', request('pertemuan', 1))
            ->get()
            ->keyBy('siswa_id');

        $rekapD = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $pertemuan = AbsensiSiswa::where('guru_id', $guru->id)
                ->whereMonth('tanggal_mulai', $bulan)
                ->whereYear('tanggal_mulai', request('tahun', now()->year))
                ->distinct('pertemuan_ke')
                ->count('pertemuan_ke');

            $hadir = AbsensiSiswa::where('guru_id', $guru->id)
                ->whereMonth('tanggal_mulai', $bulan)
                ->whereYear('tanggal_mulai', request('tahun', now()->year))
                ->where('status', 'hadir')
                ->count();

            $totalAbsensi = AbsensiSiswa::where('guru_id', $guru->id)
                ->whereMonth('tanggal_mulai', $bulan)
                ->whereYear('tanggal_mulai', request('tahun', now()->year))
                ->count();

            $persentase = $totalAbsensi > 0
                ? round(($hadir / $totalAbsensi) * 100)
                : 0;

            $rekapD[$bulan] = [
                'jumlah' => $pertemuan,
                'persentase' => $persentase
            ];
        }

        $fotoPertemuan = AbsensiPertemuan::where('guru_id', $guru->id)
            ->orderBy('tanggal_mulai')
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('F Y');
            });

        return view('guru.pelaporan', compact('user', 'guru', 'muridList', 'tahunAjaran', 'pertemuanList', 'catatanA', 'catatanB', 'pertemuan', 'absensi', 'rekapD', 'fotoPertemuan'));
    }

    public function storeLampiranA(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        $request->validate([
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:500',
            'tahun_ajaran' => 'required|string',
        ]);

        foreach ($request->catatan as $murid_id => $catatan) {

            LampiranA::updateOrCreate(
                [
                    'guru_id' => $guru->id,
                    'murid_id' => $murid_id,
                    'tahun_ajaran' => $request->tahun_ajaran
                ],
                [
                    'catatan' => $catatan
                ]
            );
        }

        return back()->with('success', 'Lampiran A berhasil disimpan');
    }

    public function storeLampiranB(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        $request->validate([
            'data' => 'required|array',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        foreach ($request->data as $murid_id => $aspekData) {
            foreach ($aspekData as $aspek => $value) {

                $nilai = $this->hitungNilaiDariKebiasaan($murid_id, $aspek);
                $deskripsi = $this->getPredikat($nilai);
                $tindak = $this->getTindak($nilai);

                LampiranB::updateOrCreate(
                    [
                        'guru_id' => $guru->id,
                        'murid_id' => $murid_id,
                        'bulan' => $request->bulan,
                        'tahun' => $request->tahun,
                        'aspek' => $aspek
                    ],
                    [
                        'nilai' => $nilai,
                        'deskripsi' => $deskripsi,
                        'tindak_lanjut' => $tindak,
                        'keterangan' => $value['keterangan'] ?? null
                    ]
                );
            }
        }
        return back()->with('success', 'Lampiran B berhasil disimpan');
    }

    public function storeLampiranC(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        foreach ($request->data as $murid_id => $value) {

            LampiranC::updateOrCreate(
                [
                    'guru_id' => $guru->id,
                    'murid_id' => $murid_id,
                    'pertemuan_ke' => $request->pertemuan
                ],
                [
                    'topik' => $value['topik'] ?? null,
                    'tindak_lanjut' => $value['tindak'] ?? null
                ]
            );
        }

        return back()->with('success', 'Lampiran C berhasil disimpan');
    }

    private function getPredikat($nilai)
    {
        if ($nilai >= 3.26) return 'Sangat Berkembang';
        if ($nilai >= 2.51) return 'Berkembang';
        if ($nilai >= 1.76) return 'Mulai Berkembang';
        return 'Belum Berkembang';
    }

    private function getTindak($nilai)
    {
        if ($nilai >= 3.26) return 'Pertahankan';
        if ($nilai >= 2.51) return 'Perlu Ditingkatkan';
        if ($nilai >= 1.76) return 'Butuh Motivasi';
        return 'Perlu Bimbingan Khusus';
    }

    private function hitungNilaiDariKebiasaan($murid_id, $aspek)
    {
        $data = KebiasaanHarian::where('user_id', $murid_id)
            ->whereMonth('tanggal', request('bulan'))
            ->whereYear('tanggal', request('tahun'))
            ->get();

        if ($data->isEmpty()) return 1;

        $totalSkor = 0;
        $jumlahParameter = 0;

        foreach ($data as $d) {

            $skor = [];

            // mapping berdasarkan aspek
            switch ($aspek) {

                case 'akademik':
                    $skor[] = $d->gemar_belajar ? 1 : 0;
                    $skor[] = $d->bangun_pagi ? 1 : 0;
                    $skor[] = $d->tidur_cepat ? 1 : 0;
                    $skor[] = $d->makan_sehat ? 1 : 0;
                    break;

                case 'karakter':
                    $skor[] = $this->skorIbadahHarian($d); // 🔥
                    $skor[] = $d->bangun_pagi ? 1 : 0;
                    $skor[] = $d->tidur_cepat ? 1 : 0;
                    $skor[] = !empty($d->bersama) ? 1 : 0;
                    break;

                case 'kedisiplinan':
                    $skor[] = $d->tidur_cepat ? 1 : 0;
                    $skor[] = $d->bangun_pagi ? 1 : 0;
                    $skor[] = $this->skorIbadahHarian($d); // 🔥
                    $skor[] = $d->berolahraga ? 1 : 0;
                    break;

                case 'sosial-emosional':
                    $skor[] = $d->tidur_cepat ? 1 : 0;
                    $skor[] = $d->berolahraga ? 1 : 0;
                    $skor[] = $d->bangun_pagi ? 1 : 0;
                    $skor[] = !empty($d->bersama) ? 1 : 0;
                    break;

                case 'potensi_minat':
                    $skor[] = $d->gemar_belajar ? 1 : 0;
                    $skor[] = $d->berolahraga ? 1 : 0;
                    $skor[] = !empty($d->bersama) ? 1 : 0;
                    break;
            }

            $totalSkor += array_sum($skor);
            $jumlahParameter += count($skor);
        }

        $rata = $totalSkor / $jumlahParameter;

        return $this->konversiNilaiRata($rata);
    }

    private function skorIbadahHarian($d)
    {
        $poin = 0;

        $poin += $d->sholat_subuh ? 1 : 0;
        $poin += $d->sholat_dzuhur ? 1 : 0;
        $poin += $d->sholat_ashar ? 1 : 0;
        $poin += $d->sholat_maghrib ? 1 : 0;
        $poin += $d->sholat_isya ? 1 : 0;
        $poin += $d->baca_quran ? 1 : 0;

        return $poin / 6; // 🔥 normalisasi jadi 0–1
    }

    private function konversiNilaiRata($rata)
    {
        if ($rata >= 0.85) return 4;
        if ($rata >= 0.70) return 3;
        if ($rata >= 0.50) return 2;
        return 1;
    }
}
