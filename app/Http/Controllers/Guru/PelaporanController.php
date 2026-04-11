<?php

namespace App\Http\Controllers\Guru;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\LampiranA;
use App\Models\User;
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

        return view('guru.pelaporan', compact('user', 'guru', 'muridList', 'tahunAjaran', 'pertemuanList', 'catatanA'));
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
}
