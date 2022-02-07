<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\HostGroupsController;
use App\Http\Controllers\Api\LocksController;
use App\Http\Controllers\Scanners\HostsController;
use App\Http\Controllers\Scanners\NeuroController;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth:sanctum')->group(function() {
    Route::delete('/auth/logout', [LoginController::class, 'logout']);
    Route::get('/auth/me', [LoginController::class, 'me']);
    Route::apiResource('users', UsersController::class, [
        'only' => ['index', 'show', 'update'],
    ]);
    Route::apiResource('host_groups', HostGroupsController::class);
    Route::get('/locks', [LocksController::class, 'index']);
    Route::delete('/locks/{lock_id}', [LocksController::class, 'destroy']);


    Route::get('/scanners/hosts/is_locked', [HostsController::class, 'isLocked']);
    Route::post('/scanners/hosts/start', [HostsController::class, 'start']);

    Route::get('/scanners/neuro/is_locked', [NeuroController::class, 'isLocked']);
    Route::post('/scanners/neuro/start', [NeuroController::class, 'start']);
    Route::get('/scanners/neuro/getHistory', [NeuroController::class, 'getHistory']);
});

Route::middleware('guest')->post('/auth/login', [LoginController::class, 'login']);
