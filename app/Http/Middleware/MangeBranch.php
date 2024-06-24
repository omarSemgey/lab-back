<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MangeBranch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('employees')->user();
        if($user === null || $user->role !== 4 ){
            return response()->json([
                'error' => 'unauthorized',
            ],400);
        }
        return $next($request);
    }
}
