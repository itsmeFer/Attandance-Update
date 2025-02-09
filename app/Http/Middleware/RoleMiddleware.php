<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect sesuai role pengguna
        return auth()->user()->role === 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/dashboard');
    }
}
