<?php

namespace App\Http\Controllers;
use App\Http\Requests\EmployeesStoreRequest;
use App\Http\Requests\EmployeesUpdateRequest;
use App\Models\Employees;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees =Employees::withCount('analyses')->with('branch')->get();

        return response()->json([
            'status' => 'success',
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeesStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $hashedPass = Hash::make($request->password);
            $employees= Employees::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPass,
                'role' => $request->role,
                'branches_id' => $request->branches_id
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'employee created successfully',
            ]);

        }catch(\Throwable $err){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
            ]);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $employee = Employees::with('branch')->find($id);
        if($employee == null){
            return response()->json([
                'status' => 'error',
                'message' => 'employee doesnt exist',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'employees' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeesUpdateRequest $request, $id)
    {
        $employee = Employees::find($id);
        if($employee == null){
            return response()->json([
                'status' => 'error',
                'message' => 'employee doesnt exist',
            ]);
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

        if(isset($request->branch_id)){
            $newData['branch_id'] = $request->branch_id;
        };

        try{
            DB::beginTransaction();
            $employee->update($newData);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'employee' => 'employee updated successfully',
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
    //     $employee = Employees::find($id);
    //     if($employee == null){
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'employee doesnt exist',
    //         ]);
    //     }
    //     try{
    //         DB::beginTransaction();
    //         $employee->delete();
    //         DB::commit();
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'employee deleted successfully'
    //         ]);
    //     }catch(\Throwable $err){
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => 'error',
    //         ]);
    //     };
    // }
}
