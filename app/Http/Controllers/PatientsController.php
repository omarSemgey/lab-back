<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientsStoreRequest;
use App\Http\Requests\PatientsUpdateRequest;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patients::withCount('analyses')->get();

        return response()->json([
            'status' => 'success',
            'patient' => $patients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientsStoreRequest $request)
    {
        try{
            $hashedPass = Hash::make($request->password);
            DB::beginTransaction();

            $patient= Patients::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPass,
                'role' => $request->role,
            ]); 

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'patient created successfully',
            ]);

        }catch(\Throwable $err){
            DB::rollBack();
            return response()->json([
                'status' => $err,
            ]);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $patient = Patients::with('analyses')->find($id);

        if($patient == null){
            return response()->json([
                'status' => 'error',
                'error' => 'patient doesnt exist',
            ],404);
        }

        return response()->json([
            'status' => 'success',
            'patient' => $patient
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientsUpdateRequest $request, $id)
    {
        $patient = Patients::find($id);

        if($patient == null){
            return response()->json([
                'status' => 'error',
                'error' => 'patient doesnt exist',
            ],404);
        }

        $newData=[];
        if(isset($request->name)){
            $newData['name'] = $request->name;
        };

        if(isset($request->email)){
            $newData['email'] = $request->email;
        };
        
        if(isset($request->password)){
            $hashedPass = Hash::make($request->password);
            $newData['password'] = $hashedPass;
        };

        if(isset($request->role)){
            $newData['role'] = $request->role;
        };

        try{
            DB::beginTransaction();

            $patient->update($newData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'patient updated successfully',
            ]);
        }catch(\Throwable $err){

            DB::rollBack();

            return response()->json([
                'status' => 'error',
            ]);
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $patient = Patients::find($id);

    //     if($patient == null){
    //         return response()->json([
    //             'status' => 'error',
    //             'error' => 'patient doesnt exist',
    //         ],404);
    //     }

    //     try{
    //         DB::beginTransaction();

    //         $patient->delete();

    //         DB::commit();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'patient deleted successfully',
    //         ]);
    //     }catch(\Throwable $err){

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => 'error',
    //         ]);
    //     };
    // }
}
