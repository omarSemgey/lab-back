<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchesStoreRequest;
use App\Models\Branches;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Branches =Branches::all();

        return response()->json([
            'status' => 'success',
            'branch' => $Branches
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchesStoreRequest $request)
    {
        try{
            DB::beginTransaction();

            $branch= Branches::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'branch' => 'branch created successfully',
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
        $Branch = Branches::with('employees')->find($id);

        if($Branch == null){
            return response()->json([
                'status' => 'error',
                'error' => 'branch doesnt exist',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'Branch' => $Branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch=Branches::find($id);

        if($branch == null){
            return response()->json([
                'status' => 'error',
                'error' => 'branch doesnt exist',
            ]);
        }

        $newData=[];
        if(isset($request->title)){
            $newData['title'] = $request->title;
        };

        if(isset($request->description)){
            $newData['description'] = $request->description;
        };

        try{
            DB::beginTransaction();

            $branch->update($newData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'branch updated successfully',
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
    //     $branch=Branches::find($id);

    //     if($branch == null){
    //         return response()->json([
    //             'status' => 'error',
    //             'error' => 'branch doesnt exist',
    //         ]);
    //     }

    //     try{
    //         DB::beginTransaction();

    //         $branch->delete();

    //         DB::commit();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'branch deleted successfully',
    //         ]);
    //     }catch(\Throwable $err){

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => 'error',
    //         ]);
    //     };
    // }
}
