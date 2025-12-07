<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('token')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión');
        }

        $user = session('user');
        
        // Solo admin y doctor pueden acceder al panel admin
        if (!in_array($user['role'], ['admin', 'doctor'])) {
            session()->forget(['token', 'user']);
            return redirect()->route('login')->with('error', 'No tienes permisos para acceder');
        }

        return $next($request);
    }
}