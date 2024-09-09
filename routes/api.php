<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This file defines all the API routes for the application. The routes are
| grouped based on their functionality and assigned appropriate middleware,
| such as 'auth:api' and role-based access control.
|
*/



// Auth routes for login, register, logout, and token refresh
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

// Admin-only routes
Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
    Route::apiResource('users', UserController::class)->only(['update']);
    Route::apiResource('tasks', TaskController::class)->except(['updateByAssignedUser']);
});

// User-only routes for task status change
Route::group(['middleware' => ['auth:api', 'role:user']], function () {
    Route::put('/tasks/{id}/changeStatus', [TaskController::class, 'updateByAssignedUser']);
});

// Manager-only routes for task assignment
Route::group(['middleware' => ['auth:api', 'role:manager']], function () {
    Route::put('/tasks/{id}/assign', [TaskController::class, 'update']);
});

// Publicly accessible routes to view users and tasks
Route::apiResource('users', UserController::class)->except(['update']);
Route::apiResource('tasks', TaskController::class)->only(['index', 'show']);
