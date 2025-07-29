<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $userRole = $user->role->role ?? null;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        // Redirect jika role salah
        return match ($userRole) {
            'admin' => redirect()->route('admin.dashboard'),
            'atasan' => redirect()->route('atasan.dashboard'),
            'pegawai' => redirect()->route('pegawai.dashboard'),
            default => abort(403, 'Unauthorized'),
        };

        // Redirect berdasarkan role yang salah akses
        // switch ($userRole) {
        //     case 'admin':
        //         return redirect()->route('admin.dashboard');
        //     case 'atasan':
        //         return redirect()->route('atasan.dashboard');
        //     case 'pegawai':
        //         return redirect()->route('pegawai.dashboard');
        //     default:
        //         return abort(403, 'Unauthorized');
        // }
    }
}
