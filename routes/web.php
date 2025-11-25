<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/register-project', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register-project', [RegistrationController::class, 'store'])->name('register.store');
