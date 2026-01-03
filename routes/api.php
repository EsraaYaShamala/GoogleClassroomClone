<?php

use App\Http\Controllers\Api\V1\AccessTokensController;
use App\Http\Controllers\Api\V1\ClassroomController;
use App\Http\Controllers\Api\V1\ClassworkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('auth/access-tokens', [AccessTokensController::class, 'index']);
        Route::delete('auth/access-tokens/{id?}', [AccessTokensController::class, 'destroy']);
        Route::apiResource('classrooms', ClassroomController::class);
        Route::apiResource('classrooms.classworks', ClassworkController::class);
    });
    Route::middleware('guest:sanctum')->group(function () {
        Route::post('auth/access-tokens', [AccessTokensController::class, 'store']);
    });
});
