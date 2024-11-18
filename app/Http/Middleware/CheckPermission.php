<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module, $permission): Response
    {
        if (auth()->user()->role_id != 1 && !$request->user()->hasRoleCRUDPermission($module, $permission)) {
            abort(401, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
