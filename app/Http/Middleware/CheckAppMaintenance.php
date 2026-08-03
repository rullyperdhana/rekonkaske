<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppMaintenance
{
    /**
     * Handle an incoming request.

     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lockFile = storage_path('app/maintenance_lock.json');

        if (file_exists($lockFile)) {
            $status = json_decode(file_get_contents($lockFile), true);

            if (!empty($status['active']) && $status['active'] === true) {
                // Izinkan rute logout dan halaman notifikasi maintenance itu sendiri agar tidak terjadi infinity loop
                if ($request->routeIs('maintenance.notice', 'logout', 'login')) {
                    return $next($request);
                }

                // Jika user login namun bukan admin/konsolidator, atau mencoba mengakses halaman lain
                if ($request->user()) {
                    if (!in_array($request->user()->role, ['admin', 'konsolidator'])) {
                        return redirect()->route('maintenance.notice');
                    }
                } else {
                    // Jika guest mengakses rute selain landing/login, atau jika kita ingin mengarahkan guest
                    // Untuk saat ini biarkan guest jika menuju halaman login, tapi begitu masuk akun SKPD akan dilempar ke maintenance.notice
                }
            }
        }

        return $next($request);
    }
}
