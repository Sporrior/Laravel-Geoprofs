<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        // Redirect or show an error if the user doesn't have permission
        return redirect('/home')->with('error', 'Je hebt geen toegang tot deze pagina.');
    }
}
