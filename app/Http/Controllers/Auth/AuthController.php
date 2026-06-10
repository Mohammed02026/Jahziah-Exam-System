<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('home.redirect');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,instructor,student'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userClass = config('auth.providers.users.model', \App\Models\User::class);

        $user = $userClass::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => bcrypt($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home.redirect');
    }

    public function logout(Request $request)
{
    // احفظ اللغة قبل ما نمسح الـ session
    $locale = $request->cookie('locale')
        ?? session('locale')
        ?? config('app.locale', 'ar');

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // رجّع اللغة للـ session الجديد + ثبّت cookie
    $request->session()->put('locale', $locale);

    return redirect()
        ->route('login')
        ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
}
}
