<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
       if(!session('email')){
            session()->put('url', url()->current());
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}