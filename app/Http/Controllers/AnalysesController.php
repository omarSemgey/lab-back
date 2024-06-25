<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalysesStoreRequest;
use App\Http\Requests\AnalysesUpdateRequest;
use App\Models\Analyses;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalysesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $analyses =Analyses::with('branch','doctor','patient')->get();

        return response()->json([
            'status' => 'success',
            'analyze' => $analyses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnalysesStoreRequest $request)
    {
        try{
            DB::beginTransaction();

                $content=$request->content;

                $originalContentName= $content->getClientOriginalName();

                if(preg_match('/\.[^.]+\./',$originalContentName)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'content is not appropriate.',
                    ],403);
                    DB::rollBack();
                }

                $contentName= Str::random(32);
                $mimeType=$content->getClientMimeType();
                $type=explode('/',$mimeType);

                $imageName= $contentName . '.' . $type[1];

                $content->move(public_path('images'),$imageName);

                $analyses= Analyses::create([
                    'title' => $request->title,
                    'content' => 'http://127.0.0.1:8000/images/' . $imageName,
                    'status' => $request->status,
                    'employees_id' => $request->employees_id,
                    'patients_id' => $request->patients_id,
                    'branches_id' => $request->branches_id,
                ]);
            

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'analyze created successfully',
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
        $analyses = Analyses::with('branch','doctor','patient')->find($id);

        if($analyses == null){
            return response()->json([
                'status' => 'error',
                'error' => 'analyses doesnt exist',
            ],404);
        }

        return response()->json([
            'status' => 'success',
            'analyze' => $analyses
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnalysesUpdateRequest $request, $id)
    {
        $analyses=Analyses::find($id);

        if($analyses == null){
            return response()->json([
                'status' => 'error',
                'error' => 'analyses doesnt exist',
            ],404);
        } 

        $newData=[];
        if(isset($request->title)){
            $newData['title'] = $request->title;
        };

        if(isset($request->content)){
            $content=$request->content;

            $originalContentName= $content->getClientOriginalName();

            if(preg_match('/\.[^.]+\./',$originalContentName)){
                throw new Exception(trans('general.notAllowedAction'),403);
            }

            $contentName= Str::random(32);
            $mimeType=$content->getClientMimeType();
            $type=explode('/',$mimeType);

            $imageName= $contentName . '.' . $type[1];

            $content->move(public_path('images'),$imageName);

            $newData['content'] = 'http://127.0.0.1:8000/images/' . $imageName;
        };

        if(isset($request->status)){
            $newData['status'] = $request->status;
        };

        if(isset($request->employees_id)){
            $newData['employees_id'] = $request->employees_id;
        };

        if(isset($request->patients_id)){
            $newData['patients_id'] = $request->patients_id;
        };

        if(isset($request->branches_id)){
            $newData['branches_id'] = $request->branches_id;
        };


        try{
            DB::beginTransaction();

            $analyses->update($newData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'analyze updated successfully',
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
    //     $analyses = Analyses::find($id);

    //     if($analyses == null){
    //         return response()->json([
    //             'status' => 'error',
    //             'error' => 'analyses doesnt exist',
    //         ],404);
    //     } 

    //     try{
    //         DB::beginTransaction();

    //         $analyses->delete();

    //         DB::commit();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'analyses deleted successfully',
    //         ]);
    //     }catch(\Throwable $err){

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => 'error',
    //         ]);
    //     };

    // }
}
