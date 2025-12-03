<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Ambil data terbaru dengan pagination
        $logs = ActivityLog::with('user')->latest()->paginate(10);
        return view('activity_logs.index', compact('logs'));
    }
}