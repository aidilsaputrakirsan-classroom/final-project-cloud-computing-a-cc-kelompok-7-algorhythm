<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login DAN role-nya adalah admin, silakan lewat
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        // Jika bukan admin, tendang ke halaman depan (Landing Page)
        return redirect('/')->with('error', 'Anda tidak memiliki akses Admin.');
    }
}