<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('Unauthorized.', null, 401);
        }

        if (!$user->is_active) {
            return ApiResponse::error('Your account has been deactivated.', null, 403);
        }

        if (!$user->hasRole($role)) {
            return ApiResponse::error('Forbidden. You do not have the required role.', null, 403);
        }

        return $next($request);
    }
}