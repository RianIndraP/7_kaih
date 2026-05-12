<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\WebsiteManagement;
use Illuminate\Support\Facades\Auth;

class CheckWebsiteLock
{
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        // Allow access to website-locked page to prevent redirect loop
        if ($request->is('website-locked')) {
            return $next($request);
        }
        
        // Check if website is locked
        if (WebsiteManagement::isWebsiteLocked()) {
            $lockMessage = WebsiteManagement::getLockMessage() 
                ?: 'Website sedang dalam masa perbaikan. Silakan coba lagi nanti.';
            
            // If user is authenticated
            if (Auth::check()) {
                $user = Auth::user();
                
                // Allow admin to access
                if ($user->isAdmin()) {
                    return $next($request);
                }
                
                // Logout non-admin users
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect to locked page after logout
                return redirect()->route('website.locked');
            }
            
            // For guests (not logged in)
            // Block POST login attempts - only admin can login when locked
            if ($request->is('login') && $request->isMethod('post')) {
                // Get identifier from request (can be NISN, NIP, NIK, or Username)
                $identifier = $request->input('identifier');
                
                if ($identifier) {
                    // Find user by any identifier field
                    $user = \App\Models\User::where(function($q) use ($identifier) {
                        $q->where('nisn', $identifier)
                          ->orWhere('nip', $identifier)
                          ->orWhere('nik', $identifier)
                          ->orWhere('username', $identifier)
                          ->orWhere('email', $identifier);
                    })->first();
                    
                    // Only allow if user exists AND logged in with username
                    // Block if logged in with NISN/NIP/NIK
                    if ($user && $user->isAdmin()) {
                        // Check if identifier matches username (admin login method)
                        if ($user->username === $identifier) {
                            return $next($request); // Allow admin login with username
                        }
                        // Block if using NISN/NIP/NIK/email even for admin
                    }
                }
                
                // Block all login attempts (non-admin or admin using NISN/NIP/NIK)
                return redirect()->route('website.locked');
            }
            
            // Allow guests to access login page (GET) and other public pages
        }
        
        return $next($request);
    }
}
