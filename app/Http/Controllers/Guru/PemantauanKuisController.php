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
            $kelasIds = \App\Models\Kelas::where('guru_id', $guru->id)->pluck('id');
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
}