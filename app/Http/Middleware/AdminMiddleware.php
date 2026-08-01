<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user tidak login atau bukan admin (berdasarkan logika baru di model User)
        if (!$user || !$user->isAdmin()) {
            abort (404); // Menyamarkan halaman admin seolah-olah tidak ada
        }

        return $next($request);
    }
}
