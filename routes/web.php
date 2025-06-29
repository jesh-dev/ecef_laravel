<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\memberController;

Route::get('/', function () {
    return view('welcome');
});



// Route::middleware(['web'])->group(function () {
//     Route::post('/api/login', [memberController::class, 'login']);
// });
