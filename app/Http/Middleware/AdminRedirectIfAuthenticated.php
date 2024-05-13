<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $redirectRoutes = [
            'admin.login',
            'admin.register',
        ];
        
        $getName = $request->route()->getName();
        $guard = Auth::guard('admin')->check();
        if ($guard && in_array($getName, $redirectRoutes)) {
            return redirect()->route("admin.dashboard");
        }
        
        return $next($request);
    }
}
