<?php

use App\Http\Controllers\Admin\userManagementController;
use App\Http\Controllers\memberController;
use App\Http\Controllers\passwordResetController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\PaymentHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [memberController::class, 'register']);
Route::post('/login', [memberController::class, 'login'])->middleware( 'throttle:3,1'); // 3 attempts per minute
Route::post('/verify', [memberController::class, 'verify']);
Route::post('/contact', [memberController::class, 'sendMail']);

Route::post('/forgot', [passwordResetController::class, 'forgottenPassword']);
Route::post('/reset', [passwordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->post('/payments', [paymentController::class, 'store']);
Route::middleware('auth:sanctum')->get('/history', [paymentController::class, 'index']);

    
    Route::middleware('auth:sanctum')->get('/admin/users', [userManagementController::class, 'allUsers']);
    Route::middleware('auth:sanctum')->delete('admin/users/{id}', [userManagementController::class, 'destroy']);
    Route::middleware('auth:sanctum')->put('admin/users/{id}', [userManagementController::class, 'update']);
    Route::middleware('auth:sanctum')->get('admin/history', [userManagementController::class, 'indexHistory']);
