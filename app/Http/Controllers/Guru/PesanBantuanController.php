<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PesanBantuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesanBantuanController extends Controller
{
    public function index(): View
    {
        return view('guru.kirim-pesan-bantuan');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pengirim' => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'in:teknis,akun,siswa,pelaporan,lainnya'],
            'judul'         => ['required', 'string', 'max:255'],
            'isi'           => ['required', 'string', 'max:2000'],
        ], [
            'nama_pengirim.required' => 'Nama pengirim wajib diisi.',
            'kategori.required'      => 'Kategori pesan wajib dipilih.',
            'kategori.in'            => 'Kategori tidak valid.',
            'judul.required'         => 'Judul pesan wajib diisi.',
            'isi.required'           => 'Isi pesan wajib diisi.',
            'isi.max'                => 'Isi pesan maksimal 2000 karakter.',
        ]);

        PesanBantuan::create([
            'user_id'       => Auth::id(),
            'nama_pengirim' => $request->nama_pengirim,
            'kategori'      => $request->kategori,
            'judul'         => $request->judul,
            'isi'           => $request->isi,
            'status'        => 'belum_ditangani',
        ]);

        return back()->with('success', 'Pesan bantuan berhasil dikirim! Kami akan segera merespons.');
    }
}
