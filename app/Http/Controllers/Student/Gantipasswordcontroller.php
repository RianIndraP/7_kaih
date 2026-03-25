<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GantiPasswordController extends Controller
{
    public function index(): View
    {
        return view('ganti-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password_lama'             => ['required', 'string'],
            'password_baru'             => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_lama.required'    => 'Password lama wajib diisi.',
            'password_baru.required'    => 'Password baru wajib diisi.',
            'password_baru.min'         => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed'   => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (! Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama tidak sesuai.'])
                ->withInput();
        }

        // Pastikan password baru berbeda dari yang lama
        if (Hash::check($request->password_baru, $user->password)) {
            return back()
                ->withErrors(['password_baru' => 'Password baru tidak boleh sama dengan password lama.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}