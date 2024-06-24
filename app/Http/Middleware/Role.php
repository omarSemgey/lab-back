<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $employee = auth('employees')->user() ;
        
        if($employee === null){
        $patient = auth('patients')->user() ;
        if($patient === null){
            return response()->json([
                'error' => $patient,
            ],400);
        }else{
            return response()->json([
                'role' => $patient->role,
                'id' => $patient->id,
            ],200);
        }
        }else{
            return response()->json([
                'role' => $employee->role,
            ],200);
        }

    }
}
