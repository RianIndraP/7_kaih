<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\WebsiteManagement;

class AuthController extends Controller
{
    public function showLogin()
    {
        // If already logged in, check if website is locked
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if website is locked (except for admin)
            if (!$user->isAdmin() && WebsiteManagement::isWebsiteLocked()) {
                Auth::logout(); // Force logout non-admin users
                return redirect()->route('website.locked');
            }
            
            return $this->redirectToDashboard($user);
        }
        return view('auth.login');
    }

    public function showWebsiteLocked()
    {
        $lockMessage = WebsiteManagement::getLockMessage() ?: 'Website sedang dalam masa perbaikan. Silakan coba lagi nanti.';
        return view('auth.website-locked', [
            'message' => $lockMessage
        ]);
    }

    private function redirectToDashboard($user)
    {
        // Check if website is locked (except for admin)
        if (!$user->isAdmin() && WebsiteManagement::isWebsiteLocked()) {
            Auth::logout(); // Force logout non-admin users
            return redirect()->route('website.locked');
        }
        
        if ($user->isSiswa()) {
            return redirect()->route('student.dashboard');
        } else if ($user->isKepalaSekolah()) {
            return redirect()->route('kepala-sekolah.dashboard');
        } else if ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        } else if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect('/');
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

        try {
            $passwordValid = $user && Hash::check($password, $user->password);
        } catch (\RuntimeException $e) {
            // Password tidak pakai Bcrypt, cek plain text (untuk development) atau reject
            $passwordValid = false;
        }

        if ($passwordValid) {
            // Check if website is locked (except for admin)
            if (!$user->isAdmin() && WebsiteManagement::isWebsiteLocked()) {
                $lockMessage = WebsiteManagement::getLockMessage() ?: 'Website sedang dalam masa perbaikan. Silakan coba lagi nanti.';
                
                return view('auth.website-locked', [
                    'message' => $lockMessage
                ]);
            }
            
            Auth::login($user);
            
            // Redirect based on user type
            if ($user->isSiswa()) {
                return redirect()->route('student.dashboard');
            } else if ($user->isKepalaSekolah()) {
                return redirect()->route('kepala-sekolah.dashboard');
            } else if ($user->isGuru()) {
                return redirect()->route('guru.dashboard');
            } else if ($user->isAdmin()) {
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
            ->orWhere('nik', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if ($user) {
            // Check if user has email
            if (empty($user->email)) {
                return back()->withErrors([
                    'identifier' => 'Akun ini tidak memiliki email. Silakan hubungi admin untuk reset password.',
                ]);
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store user and OTP in session
            session(['forgot_user' => $user]);
            session(['forgot_otp' => $otp]);
            session(['forgot_otp_expires_at' => now()->addMinutes(10)]);

            // Send OTP via email
            try {
                Mail::html("<h1>Kode OTP untuk reset password Anda</h1><p style='font-size: 18px;'>Kode OTP Anda adalah:</p><p style='font-size: 32px; font-weight: bold; color: #1e40af;'>{$otp}</p><p style='font-size: 16px;'>Kode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun.</p><hr><p style='font-size: 14px; color: #666;'>7 Kebiasaan Anak Indonesia Hebat<br>SMK Negeri 5 Telkom Banda Aceh</p>", function($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Reset Password - 7 Kebiasaan Anak Indonesia Hebat');
                });
            } catch (\Exception $e) {
                // If email fails, still proceed but show warning
                return back()->withErrors([
                    'identifier' => 'Gagal mengirim email OTP. Silakan coba lagi atau hubungi admin.',
                ]);
            }

            return redirect()->route('verify-data');
        }

        return back()->withErrors([
            'identifier' => 'Identitas tidak ditemukan.',
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
            'otp' => 'required|string|size:6',
        ]);

        $user = session('forgot_user');
        $otp = session('forgot_otp');
        $expiresAt = session('forgot_otp_expires_at');

        if (!$user || !$otp) {
            return redirect()->route('forgot-password');
        }

        // Check if OTP expired
        if (now()->gt($expiresAt)) {
            return back()->withErrors([
                'otp' => 'Kode OTP telah kadaluarsa. Silakan minta kode baru.',
            ]);
        }

        // Verify OTP
        if ($request->otp === $otp) {
            // Clear OTP from session
            session()->forget('forgot_otp');
            session()->forget('forgot_otp_expires_at');
            return redirect()->route('create-new-password');
        }

        return back()->withErrors([
            'otp' => 'Kode OTP tidak valid.',
        ]);
    }

    public function resendOTP(Request $request)
    {
        $user = session('forgot_user');
        if (!$user) {
            return redirect()->route('forgot-password');
        }

        // Check if user has email
        if (empty($user->email)) {
            return back()->withErrors([
                'identifier' => 'Akun ini tidak memiliki email. Silakan hubungi admin untuk reset password.',
            ]);
        }

        // Generate new 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update OTP in session
        session(['forgot_otp' => $otp]);
        session(['forgot_otp_expires_at' => now()->addMinutes(10)]);

        // Send OTP via email
        try {
            Mail::html("<h1>Kode OTP untuk reset password Anda</h1><p style='font-size: 18px;'>Kode OTP Anda adalah:</p><p style='font-size: 32px; font-weight: bold; color: #1e40af;'>{$otp}</p><p style='font-size: 16px;'>Kode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun.</p><hr><p style='font-size: 14px; color: #666;'>7 Kebiasaan Anak Indonesia Hebat<br>SMK Negeri 5 Telkom Banda Aceh</p>", function($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode OTP Reset Password - 7 Kebiasaan Anak Indonesia Hebat');
            });
        } catch (\Exception $e) {
            return back()->withErrors([
                'otp' => 'Gagal mengirim email OTP. Silakan coba lagi.',
            ]);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
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
