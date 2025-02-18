<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->is_admin) {
            return redirect('/clientes');
        }

        return $next($request);
    }
} 