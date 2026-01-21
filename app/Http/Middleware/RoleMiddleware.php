<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
