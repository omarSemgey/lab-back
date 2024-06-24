<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientsStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patients;
use Illuminate\Support\Facades\DB;

class PatientsAuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('patients')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $patient = Auth::guard('patients')->user();
        return response()->json([
                'status' => 'success',
                'role' => $patient->role,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
    }

    public function register(PatientsStoreRequest $request){
        try{
            DB::beginTransaction();
            $patient = Patients::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'branches_id' => $request->branches_id,
            ]);
            DB::commit();
            $token = Auth::guard('patients')->login($patient);
            return response()->json([
                'status' => 'success',
                'message' => 'patient created successfully',
                'role' => $patient->role,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }catch(\Throwable $err){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('patients')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'patient' => Auth::guard('patients')->user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}