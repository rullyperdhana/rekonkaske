<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = \Spatie\Activitylog\Models\Activity::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pengaturan.log.index', compact('logs'));
    }
}
