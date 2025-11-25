<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Mengambil semua data log, diurutkan dari yang terbaru
        $activities = Activity::with('causer')->latest()->paginate(10);

        return view('activity_log.index', compact('activities'));
    }
}