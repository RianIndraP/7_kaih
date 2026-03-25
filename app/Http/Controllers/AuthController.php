<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $credentials['identifier'];
        $password = $credentials['password'];

        // Try to find user by various identifiers
        $user = User::where('nisn', $identifier)
            ->orWhere('nip', $identifier)
            ->orWhere('nik', $identifier)
            ->orWhere('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            
            // Redirect based on user type
            if ($user->isSiswa()) {
                return redirect()->route('student.dashboard');
            } else if ($user->isGuru()) {
                return redirect()->route('guru.dashboard');
            } else if ($user->username === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'identifier' => 'Identitas atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;
        $user = User::where('nisn', $identifier)
            ->orWhere('nip', $identifier)
            ->first();

        if ($user) {
            // Store user info in session for next steps
            session(['forgot_user' => $user]);
            return redirect()->route('verify-data');
        }

        return back()->withErrors([
            'identifier' => 'NISN/NIP tidak ditemukan.',
        ]);
    }

    public function showVerifyData()
    {
        $user = session('forgot_user');
        if (!$user) {
            return redirect()->route('forgot-password');
        }

        return view('auth.verify-data', ['user' => $user]);
    }

    public function verifyData(Request $request)
    {
        $request->validate([
            'birth_date' => 'required|date',
        ]);

        $user = session('forgot_user');
        if (!$user) {
            return redirect()->route('forgot-password');
        }

        if ($user->birth_date && \Carbon\Carbon::parse($user->birth_date)->format('m/d/Y') === $request->birth_date) {
            return redirect()->route('create-new-password');
        }

        return back()->withErrors([
            'birth_date' => 'Tanggal lahir tidak cocok.',
        ]);
    }

    public function showCreateNewPassword()
    {
        $user = session('forgot_user');
        if (!$user) {
            return redirect()->route('forgot-password');
        }

        return view('auth.create-new-password', ['user' => $user]);
    }

    public function createNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = session('forgot_user');
        if (!$user) {
            return redirect()->route('forgot-password');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget('forgot_user');

        return redirect()->route('password-success');
    }

    public function showPasswordSuccess()
    {
        return view('auth.password-success');
    }
}
