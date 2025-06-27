<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$requiredRoles): Response
    {
        if (!session()->has('roles')) {
            return redirect()->route('admin.login');
        }

        $userRoles = session('roles');

        foreach ($requiredRoles as $requiredRole) {
            if (in_array($requiredRole, $userRoles)) {
                return $next($request);
            }
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}
