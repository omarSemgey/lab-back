<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MangeDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('employees')->user();
        if($user === null || $user->role <= 2 ){
            return response()->json([   
                'error' => 'unauthorized',
                'role' => $user,
            ],400);
        }
        return $next($request);
    }
}
