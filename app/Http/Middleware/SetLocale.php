<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale')
            ?? $request->cookie('locale')
            ?? config('app.locale', 'ar');

        if (!in_array($locale, ['ar', 'en'], true)) {
            $locale = config('app.locale', 'ar');
        }

        App::setLocale($locale);

        // مهم: إذا جاي من cookie (مثلاً بعد logout) رجّعه للـ session
        if (!session()->has('locale') && $request->cookie('locale')) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}