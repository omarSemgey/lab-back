<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeesStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employees;
use Illuminate\Support\Facades\DB;

class EmployeesAuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('employees')->attempt($credentials);   
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $employees = Auth::guard('employees')->user();
        return response()->json([
                'status' => 'success',
                'role' => $employees->role,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    // public function register(EmployeesStoreRequest $request){
    //     try{
    //         DB::beginTransaction();
    //         $employee = Employees::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'role' => $request->role,
    //             'branches_id' => $request->branches_id,
    //         ]);
    //         DB::commit();
    //         $token = Auth::guard('employees')->login($employee);
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'employee created successfully',
    //             'role' => $employee->role,
    //             'authorisation' => [
    //                 'token' => $token,
    //                 'type' => 'bearer',
    //             ]
    //         ]);
    //     }catch(\Throwable $err){
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => $err,
    //         ]);
    //     }
    // }

    public function logout()
    {
        Auth::guard('employees')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'employee' => Auth::guard('employees')->user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}