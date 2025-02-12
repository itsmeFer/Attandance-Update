<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect hanya jika user tidak memiliki role yang sesuai
        return redirect()->route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'karyawan.dashboard');
    }
}
