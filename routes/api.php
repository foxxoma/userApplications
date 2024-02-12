<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User;
use App\Http\Controllers\Api\V1\ApplicationController;

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

Route::get('/user',User\ShowController::class)->middleware('auth:sanctum');
Route::post('/register',User\CreateController::class);
Route::post('/login', User\LoginController::class);
Route::get('/logout', User\LogoutController::class)->middleware('auth:sanctum');

Route::apiResource('applications', ApplicationController::class)->only([
    'index', 'show', 'store'
]);

Route::post('applications/comment}', [ApplicationController::class, 'comment']);