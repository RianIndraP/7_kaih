<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\PesanBantuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KirimPesanBantuanController extends Controller
{
    public function index(): View
    {
        return view('kepala-sekolah.kirim-pesan-bantuan');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pengirim' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'in:teknis,akun,kebiasaan,profil,lainnya'],
            'judul' => ['required', 'string', 'max:100'],
            'isi' => ['required', 'string', 'max:1000'],
        ], [
            'nama_pengirim.required' => 'Nama pengirim wajib diisi.',
            'kategori.required' => 'Kategori pesan wajib dipilih.',
            'judul.required' => 'Judul pesan wajib diisi.',
            'judul.max' => 'Judul pesan maksimal 100 karakter.',
            'isi.required' => 'Isi pesan wajib diisi.',
            'isi.max' => 'Isi pesan maksimal 1000 karakter.',
        ]);

        PesanBantuan::create([
            'user_id' => Auth::id(),
            'nama_pengirim' => $request->nama_pengirim,
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'isi' => $request->isi,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pesan bantuan berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }
}
