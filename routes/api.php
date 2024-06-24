<?php

use App\Http\Controllers\AnalysesController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\EmployeesAuthController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\PatientsAuthController;
use App\Http\Controllers\PatientsController;
use App\Http\Middleware\MangeBranch;
use App\Http\Middleware\MangeDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// branches
    Route::apiResource('Branches',BranchesController::class);
    Route::post('Branches','App\Http\Controllers\BranchesController@store'::class)->middleware(MangeBranch::class);
    Route::put('Branches','App\Http\Controllers\BranchesController@update'::class)->middleware(MangeBranch::class);
    // Route::delete('Branches','App\Http\Controllers\BranchesController@delete'::class)->middleware(MangeBranch::class);

// employees
    Route::apiResource('Employees',EmployeesController::class);
    Route::post('Employees','App\Http\Controllers\EmployeesController@store'::class)->middleware(MangeDoctor::class);
    Route::put('Employees','App\Http\Controllers\EmployeesController@update'::class)->middleware(MangeDoctor::class);
    // Route::delete('Employees','App\Http\Controllers\EmployeesController@delete'::class)->middleware(MangeDoctor::class);

// patients
    Route::apiResource('Patients',PatientsController::class);
    Route::post('Patients','App\Http\Controllers\PatientsController@store'::class)->middleware('auth:employees');
    Route::put('Patients','App\Http\Controllers\PatientsController@update'::class)->middleware('auth:employees');
    // Route::delete('Patients','App\Http\Controllers\PatientsController@store'::class)->middleware('auth:employees,patients');

// analyses
Route::apiResource('Analyses',AnalysesController::class);
Route::post('Analyses','App\Http\Controllers\AnalysesController@store'::class)->middleware('auth:employees,patients');
Route::put('Analyses','App\Http\Controllers\AnalysesController@update'::class)->middleware('auth:employees');
Route::delete('Analyses','App\Http\Controllers\AnalysesController@delete'::class)->middleware('auth:employees,patients');

// auth
Route::controller(EmployeesAuthController::class)->group(function () {
    Route::post('Employees/login', 'App\Http\Controllers\EmployeesAuthController@login');
    // Route::post('Employees/register', 'App\Http\Controllers\EmployeesAuthController@register');
    Route::post('Employees/logout', 'App\Http\Controllers\EmployeesAuthController@logout')->middleware('auth:employees');
    Route::post('Employees/refresh', 'App\Http\Controllers\EmployeesAuthController@refresh')->middleware('auth:employees');
});

Route::controller(PatientsAuthController::class)->group(function () {
    Route::post('Patients/login', 'App\Http\Controllers\PatientsAuthController@login');
    Route::post('Patients/register', 'App\Http\Controllers\PatientsAuthController@register');
    Route::post('Patients/logout', 'App\Http\Controllers\PatientsAuthController@logout')->middleware('auth:patients');
    Route::post('Patients/refresh', 'App\Http\Controllers\PatientsAuthController@refresh')->middleware('auth:patients');
});

//role
Route::post('role', 'App\Http\Controllers\Role@role')->middleware('Role');