<?php

use App\Http\Controllers\memberController;
use App\Http\Controllers\paymentController;
use App\Models\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})
->middleware('auth:sanctum',);

// Route::middleware(['auth:sanctum'])->get('/admin/total-users', function () {
//     return response()->json([
//         'count' => \App\Models\User::count()
//     ]);
// });


Route::post('/register', [memberController::class, 'register']);
Route::post('/login', [memberController::class, 'login'])
->middleware('guest', 'throttle:5,1'); // 5 attempts per minute
Route::post('/verify', [memberController::class, 'verify']);

Route::post('/payment', [paymentController::class, 'payment']);

Route::post('/contact', [memberController::class, 'sendMail']);
// Route::post('/contact', [memberController::class, 'index'])->name('contact');

