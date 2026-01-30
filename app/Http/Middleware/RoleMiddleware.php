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
     */
    
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->user();
	
	if ($user && $user->hasRole('general_manager')) {
            return $next($request);
        }

        // بررسی اینکه کاربر وارد شده و دارای نقش مورد نظر است
        if ($user && $user->hasRole($role)) {
            return $next($request);
        }

        // اگر دسترسی نداشت
	// return response()->json(['error' => 'دسترسی غیرمجاز'], 403);
	abort(403);
    }
}
