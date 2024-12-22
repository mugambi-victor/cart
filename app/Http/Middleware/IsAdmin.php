<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check user is authenticated and has an 'admin' role
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); 
        }

        // If not an admin, return a JSON 403 status
        return response()->json([
            'error' => 'You do not have admin access.'
        ], 403);
    }
}