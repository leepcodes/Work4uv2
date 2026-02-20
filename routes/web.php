<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\StudentController;
use App\Http\Controllers\Auth\TutorVerificationController;

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
    Route::get('/verification-step-1', [TutorVerificationController::class, 'step1'])->name('tutor.verification-1');
    Route::post('/verification-step-1', [TutorVerificationController::class, 'step1Store'])->name('tutor.verification-1.store');
    Route::get('/verification-step-2', [TutorVerificationController::class, 'step2'])->name('tutor.verification-2');
    Route::post('/verification-step-2', [TutorVerificationController::class, 'step2Store'])->name('tutor.verification-2.store');
    Route::get('/verification-step-3', [TutorVerificationController::class, 'step3'])->name('tutor.verification-3');
    Route::post('/verification-step-3', [TutorVerificationController::class, 'step3Store'])->name('tutor.verification-3.store');
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