<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserPermissions
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if ($permission === 'edit' && !auth()->user()->can_edit && !auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'No tienes permisos de edición.');
        }

        if ($permission === 'admin' && !auth()->user()->is_admin) {
            return redirect()->back()->with('error', 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
} 