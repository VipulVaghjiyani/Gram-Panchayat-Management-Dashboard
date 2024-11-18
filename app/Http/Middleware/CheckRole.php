<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module): Response
    {
        if (auth()->user()->role_id != 1 && !$request->user()->hasRolePermission($module)) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}
