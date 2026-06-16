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
        $guru = Auth::user()->guru;

        // Ambil semua kelas yang diwalikan guru ini
        $kelasIds = \App\Models\Kelas::where('guru_id', $guru->id)->pluck('id');
        $siswaList = \App\Models\User::whereIn('kelas_id', $kelasIds)->whereNotNull('nisn')->orderBy('name')->get();

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
            'totalSiswa' => $siswaList->count(),
            'totalSudah' => $siswaList->count() ? $data->where('status', 'sudah_dikerjakan')->count() : 0,
        ]);
    }
}