<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $globalActivities = collect();
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Hanya tampilkan Live Log di halaman Dashboard
        if ($user && in_array($user->role, ['admin', 'konsolidator']) && request()->routeIs('dashboard')) {
            $pengaturan = \App\Models\Pengaturan::whereNull('skpd_id')->first() ?? \App\Models\Pengaturan::first();
            $isLiveLogActive = $pengaturan ? $pengaturan->is_livelog_active : true;

            if ($isLiveLogActive) {
                $globalActivities = \App\Models\Transaksi::with(['skpd', 'user'])
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();
            }
        }

        return view('layouts.app', compact('globalActivities'));
    }
}
