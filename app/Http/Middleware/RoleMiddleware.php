<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $current = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        if ($current !== $role) {
            abort(403);
        }

        return $next($request);
    }
}
