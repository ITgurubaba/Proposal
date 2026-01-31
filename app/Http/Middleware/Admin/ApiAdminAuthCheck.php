<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAdminAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin())
        {
            return $next($request);
        }

        return response()->json([
            'success'=>false,
            'message'=>'Authentication Failed'
        ],400);

    }
}
