<?php

namespace App\Http\Controllers\Student;
 
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
        return view('kirim-pesan-bantuan');
    }
 
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'kategori'      => ['required', 'string', 'in:teknis,akun,kebiasaan,profil,lainnya'],
            'judul'         => ['required', 'string', 'max:255'],
            'isi'           => ['required', 'string', 'max:2000'],
        ], [
            'kategori.required'      => 'Kategori pesan wajib dipilih.',
            'judul.required'         => 'Judul pesan wajib diisi.',
            'isi.required'           => 'Isi pesan wajib diisi.',
            'isi.max'                => 'Isi pesan maksimal 2000 karakter.',
        ]);
 
        PesanBantuan::create([
            'user_id'       => Auth::id(),
            'nama_pengirim' => Auth::user()->name,
            'kategori'      => $request->kategori,
            'judul'         => strip_tags($request->judul),
            'isi'           => strip_tags($request->isi),
            'status'        => 'belum_ditangani',
        ]);
 
        return back()->with('success', 'Pesan bantuan berhasil dikirim! Kami akan segera merespons.');
    }
}