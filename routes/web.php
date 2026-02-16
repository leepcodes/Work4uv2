<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;


Route::get('/', function () {
    return Inertia::render('auth/register', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('register');

//landing page

Route::get('/', function () {
    return Inertia::render('landingpage/index');
})->name('landingpage');


//register
Route::get('/register', [RegisterController::class, 'create'])->name('register');  
Route::post('/register', [RegisterController::class, 'store']);



Route::get('/login', function () {
    return Inertia::render('auth/login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

require __DIR__.'/settings.php';