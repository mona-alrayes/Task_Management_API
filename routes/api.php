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
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::group(['middleware' => [ 'auth:api','role:admin']], function () {
    Route::apiResource('users', UserController::class)->only('update');
    Route::apiResource('tasks', TaskController::class)->except('updateByAssignedUser');
});

Route::group(['middleware' => [ 'auth:api','role:user']], function () {
    Route::put('/tasks/{id}/changeStatus', [TaskController::class, 'updateByAssignedUser']);
});
Route::group(['middleware' => [ 'auth:api','role:manager']], function () {
    Route::put('/tasks/{id}/assign', [TaskController::class, 'update']);
});
Route::apiResource('users', UserController::class)->except('update');
Route::apiResource('tasks', TaskController::class)->only(['index', 'show']);
