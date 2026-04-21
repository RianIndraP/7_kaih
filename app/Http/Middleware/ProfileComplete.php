<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isSiswa()) {
            $allowedRoutes = [
                'student.dashboard',
                'student.profile',
                'student.profile.save',
                'student.profile.delete-location',
                'student.kirim-pesan-bantuan',
                'student.kirim-pesan-bantuan.store',
            ];

            $currentRoute = $request->route() ? $request->route()->getName() : null;

            if (!$user->isProfileComplete() && $currentRoute && !in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('student.dashboard')
                    ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu untuk mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}
