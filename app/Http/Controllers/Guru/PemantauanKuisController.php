<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\JawabanKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemantauanKuisController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        // Dapatkan ID guru yang valid
        $guruId = $guru ? $guru->id : (\App\Models\Guru::where('user_id', $user->id)->first()->id ?? $user->id);

        // Ambil daftar siswa yang berada di bawah bimbingan guru ini
        $siswaList = \App\Models\User::where('guru_wali_id', $guruId)
            ->whereNotNull('nisn')
            ->orderBy('name')
            ->get();

        // Fallback: Jika masih kosong, coba ambil lewat relasi tabel Kelas
        if ($siswaList->isEmpty() && $guru) {
            $kelasIds = $guru->kelas->pluck('id');
            $siswaList = \App\Models\User::whereIn('kelas_id', $kelasIds)
                ->whereNotNull('nisn')
                ->orderBy('name')
                ->get();
        }

        $kuisList = Kuis::where('waktu_mulai', '<=', now())->orderBy('waktu_mulai', 'desc')->get();

        $selectedKuisId = $request->input('kuis_id', $kuisList->first()?->id);
        $selectedKuis = $kuisList->firstWhere('id', $selectedKuisId);

        $statusFilter = $request->input('status');

        $data = $siswaList->map(function ($siswa) use ($selectedKuis) {
            $jawaban = $selectedKuis
                ? JawabanKuis::where('kuis_id', $selectedKuis->id)
                    ->where('siswa_id', $siswa->id)
                    ->first()
                : null;

            return [
                'siswa' => $siswa,
                'jawaban' => $jawaban,
                'status' => $jawaban?->status ?? 'belum_dikerjakan',
            ];
        });

        // Calculate totals BEFORE filtering so the dashboard numbers remain accurate
        $totalSiswa = $siswaList->count();
        $totalSudah = $data->where('status', 'sudah_dikerjakan')->count();

        if ($statusFilter) {
            if ($statusFilter === 'sudah') {
                $data = $data->filter(fn($d) => $d['status'] === 'sudah_dikerjakan');
            } elseif ($statusFilter === 'belum') {
                $data = $data->filter(fn($d) => $d['status'] !== 'sudah_dikerjakan');
            }
        }

        return view('guru.kuis.index', [
            'kuisList' => $kuisList,
            'selectedKuis' => $selectedKuis,
            'data' => $data,
            'totalSiswa' => $totalSiswa,
            'totalSudah' => $totalSudah,
        ]);
    }

    public function cetak(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        // Dapatkan ID guru yang valid
        $guruId = $guru ? $guru->id : (\App\Models\Guru::where('user_id', $user->id)->first()->id ?? $user->id);

        // Ambil daftar siswa yang berada di bawah bimbingan guru ini
        $siswaList = \App\Models\User::where('guru_wali_id', $guruId)
            ->whereNotNull('nisn')
            ->orderBy('name')
            ->get();

        // Fallback: Jika masih kosong, coba ambil lewat relasi tabel Kelas
        if ($siswaList->isEmpty() && $guru) {
            $kelasIds = \App\Models\Kelas::where('guru_id', $guru->id)->pluck('id');
            $siswaList = \App\Models\User::whereIn('kelas_id', $kelasIds)
                ->whereNotNull('nisn')
                ->orderBy('name')
                ->get();
        }

        $kuisList = Kuis::where('waktu_mulai', '<=', now())->orderBy('waktu_mulai', 'desc')->get();

        $selectedKuisId = $request->input('kuis_id', $kuisList->first()?->id);
        
        if (!$selectedKuisId) {
            return redirect()->route('guru.pemantauan-kuis')->with('error', 'ID kuis tidak ditemukan. Silakan pilih kuis terlebih dahulu.');
        }
        
        $selectedKuis = $kuisList->firstWhere('id', $selectedKuisId);
        
        if (!$selectedKuis) {
            return redirect()->route('guru.pemantauan-kuis')->with('error', 'Kuis tidak ditemukan. Silakan pilih kuis yang valid.');
        }

        $statusFilter = $request->input('status');

        $data = $siswaList->map(function ($siswa) use ($selectedKuis) {
            $jawaban = $selectedKuis
                ? JawabanKuis::where('kuis_id', $selectedKuis->id)
                    ->where('siswa_id', $siswa->id)
                    ->first()
                : null;

            return [
                'siswa' => $siswa,
                'jawaban' => $jawaban,
                'status' => $jawaban?->status ?? 'belum_dikerjakan',
            ];
        });

        if ($statusFilter) {
            if ($statusFilter === 'sudah') {
                $data = $data->filter(fn($d) => $d['status'] === 'sudah_dikerjakan');
            } elseif ($statusFilter === 'belum') {
                $data = $data->filter(fn($d) => $d['status'] !== 'sudah_dikerjakan');
            }
        }

        return view('guru.kuis.cetak', [
            'selectedKuis' => $selectedKuis,
            'data' => $data,
            'statusFilter' => $statusFilter,
            'guru' => $guru, // tambahkan ini
        ]);
    }
    public function laporanSiswa(Request $request, $siswaId)
    {
        $user = Auth::user();
        $guru = \App\Models\Guru::where('user_id', $user->id)->firstOrFail();

        $siswa = \App\Models\User::findOrFail($siswaId);

        $kuisId = $request->get('kuis_id');
        
        if (!$kuisId) {
            // Try to get the first available quiz
            $firstKuis = Kuis::where('waktu_mulai', '<=', now())->orderBy('waktu_mulai', 'desc')->first();
            if ($firstKuis) {
                return redirect()->to(route('guru.pemantauan-kuis.laporan', $siswaId) . '?kuis_id=' . $firstKuis->id);
            }
            return redirect()->route('guru.pemantauan-kuis')->with('error', 'ID kuis tidak ditemukan. Silakan pilih kuis terlebih dahulu.');
        }
        
        $selectedKuis = Kuis::findOrFail($kuisId);

        $jawaban = JawabanKuis::where('kuis_id', $kuisId)
            ->where('siswa_id', $siswaId)
            ->first();

        $row = [
            'siswa' => $siswa,
            'jawaban' => $jawaban,
            'status' => $jawaban?->status ?? 'belum_dikerjakan',
        ];

        return view('guru.kuis.laporan-siswa', compact('row', 'selectedKuis', 'guru'));
    }
}