<?php

use App\Http\Controllers\Api\Admin\AdminContrller;
use App\Http\Controllers\Api\Admin\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'jwt.verify.token'], function () {
    Route::post('/admin/register', [AdminContrller::class, 'register']);
    Route::get('/admin/login/test', [LoginController::class, 'test']);
});

Route::post('/admin/login', [LoginController::class, 'login']);