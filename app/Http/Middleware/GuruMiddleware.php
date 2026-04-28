<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuruMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->isGuru()) {
            abort(404);
        }

        // Check if guru has filled email and phone number
        $allowedRoutes = [
            'guru.dashboard',
            'guru.profil',
            'guru.profil.save',
            'guru.profil.delete-location',
            'guru.kirim-pesan-bantuan',
            'guru.kirim-pesan-bantuan.store',
        ];

        if (empty($user->email) || empty($user->no_telepon)) {
            $currentRoute = $request->route() ? $request->route()->getName() : null;

            if ($currentRoute && !in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('guru.dashboard')
                    ->with('warning', 'Silakan lengkapi email dan nomor telepon Anda untuk mengakses fitur lainnya.');
            }
        }

        return $next($request);
    }
}
