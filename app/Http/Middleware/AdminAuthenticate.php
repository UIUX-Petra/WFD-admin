<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
       // 1. Periksa apakah session 'token' ada DAN tidak kosong.
        if ($request->session()->has('token') && !empty($request->session()->get('token'))) {
            // 2. Jika ada, izinkan request untuk melanjutkan ke controller tujuan.
            return $next($request);
        }
        // 3. Jika tidak ada, alihkan pengguna ke halaman login admin.
        return redirect()->route('admin.login')->with('error', 'You must be logged in as an admin to access this page.');
    }
}