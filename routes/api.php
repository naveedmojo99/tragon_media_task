<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\TaskRemarkController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// authentication part of user
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->middleware(['auth:api']);
Route::post('/refresh',[AuthController::class,'refresh'])->middleware(['auth:api']);


Route::middleware('auth:api')->group(function () {
    // Project Routes
    Route::apiResource('projects', ProjectController::class);

    // Task Routes
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    // Remarks Routes
    Route::post('/tasks/{task}/remarks', [TaskRemarkController::class, 'store']);
    Route::get('/tasks/{task}/remarks', [TaskRemarkController::class, 'index']);

    // Reports
    Route::get('/projects/{project}/report', [ReportController::class, 'show']);
});