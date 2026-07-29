<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordReset
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && is_null(Auth::user()->password_changed_at)) {
            // Biarkan user mengakses halaman ganti password dan logout
            if (!$request->routeIs('password.edit') && 
                !$request->routeIs('password.update') && 
                !$request->routeIs('logout')) {
                return redirect()->route('password.edit')
                    ->with('error', 'Demi keamanan, Anda diwajibkan untuk mengganti kata sandi bawaan (default) sebelum dapat menggunakan aplikasi.');
            }
        }

        return $next($request);
    }
}
