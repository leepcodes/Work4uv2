<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\StudentController;

// Landing Page
Route::get('/', fn() => Inertia::render('landingpage/index'))->name('landingpage');

//Student
Route::middleware(['authorization:student'])->group(function () {
    Route::get('/student', fn() => Inertia::render('landingpage/student/studentlanding'))
         ->name('student.landing');
});

//Fetch data in student landinpage
Route::middleware(['authorization:student'])->group(function () {
    Route::get('/student', [StudentController::class, 'index'])
         ->name('student.landing');
});

// Tutor
Route::middleware(['authorization:tutor'])->group(function () {
    Route::get('/verification', [LoginController::class, 'index'])
         ->name('tutor.dashboard');
});


// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
Route::get('/login', fn() => Inertia::render('auth/login'))->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Logout
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

require __DIR__.'/settings.php';