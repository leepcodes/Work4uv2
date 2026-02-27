<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\StudentController;
use App\Http\Controllers\Auth\TutorVerificationController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TutorController;

// Landing Page
Route::get('/', function () {
    return redirect()->route('login');
});

// Student
Route::middleware(['authorization:student'])->group(function () {
    Route::get('/student', [StudentController::class, 'index'])->name('student.landing');
    Route::get('/tutor/profile/{uuid}', [StudentController::class, 'profile'])->name('tutor.profile');
    Route::get('/subject/{uuid}', [SubjectController::class, 'showmysubj'])->name('subject.show');

    // Request tutoring

    Route::get('/student/request', [RequestController::class, 'requests'])->name('student.request');
    Route::post('/subject/request', [RequestController::class, 'store'])->name('subject.request.store');
    Route::post('/student/request/decline', [RequestController::class, 'declineOffer'])->name('student.request.decline');
Route::post('/student/request/accept',  [RequestController::class, 'acceptOffer'])->name('student.request.accept');

});

// Tutor
Route::middleware(['authorization:tutor'])->group(function () {

    Route::get('/tutor', [TutorVerificationController::class, 'index'])->name('tutor.index');
    Route::get('/tutor/landing', [TutorVerificationController::class, 'landing'])->name('tutor.landing');

    Route::get('/verification-step-1',  [TutorVerificationController::class, 'step1'])->name('tutor.verification-1');
    Route::post('/verification-step-1', [TutorVerificationController::class, 'step1Store'])->name('tutor.verification-1.store');

    Route::get('/verification-step-2',  [TutorVerificationController::class, 'step2'])->name('tutor.verification-2');
    Route::post('/verification-step-2', [TutorVerificationController::class, 'step2Store'])->name('tutor.verification-2.store');

    Route::get('/verification-step-3',  [TutorVerificationController::class, 'step3'])->name('tutor.verification-3');
    Route::post('/verification-step-3', [TutorVerificationController::class, 'step3Store'])->name('tutor.verification-3.store');

    
    Route::get('/tutor/my-subject', [SubjectController::class, 'subject'])->name('tutor.subject');
    Route::get('/tutor/create-subject', [SubjectController::class, 'createsubject'])->name('tutor.create-subject');
    Route::post('/tutor/subjects/store', [SubjectController::class, 'store'])->name('tutor.subjects.store');

    Route::get('/tutor/request', [RequestController::class, 'tutorRequests'])->name('tutor.request');    
    Route::post('/tutor/request/offer', [RequestController::class, 'makeOffer'])->name('tutor.request.offer');

    Route::get('/tutor/my-students', [TutorVerificationController::class, 'mystudents'])->name('tutor.mystudents');
});

// Register
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
Route::get('/login', fn() => Inertia::render('auth/login'))->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Logout
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

require __DIR__.'/settings.php';