<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

Route::middleware([SetLocale::class])->group(function () {

    // Auth pages
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');

        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    });

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // Language switch (session + cookie so it persists after logout)
    Route::get('/lang/{locale}', function ($locale) {
        if (!in_array($locale, ['ar', 'en'], true)) {
            abort(404);
        }

        Session::put('locale', $locale);
        Cookie::queue('locale', $locale, 60 * 24 * 365); // 1 year
        App::setLocale($locale);

        return redirect()->back();
    })->name('lang.switch');

    // Home redirect
    Route::get('/', function () {
        return redirect()->route('home.redirect');
    });

    Route::get('/home', function () {
        $user = auth()->user();

        if (!$user) return redirect()->route('login');

        return match ($user->role->value) {
            'admin' => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    })->middleware('auth')->name('home.redirect');

    // Routes files
    require __DIR__ . '/student.php';
    require __DIR__ . '/instructor.php';
    require __DIR__ . '/admin.php';
});