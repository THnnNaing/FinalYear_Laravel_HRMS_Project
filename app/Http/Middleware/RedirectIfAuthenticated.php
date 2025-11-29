<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                switch ($user->user_type) {
                    case 'admin':
                        return redirect(route('admin.dashboard'));
                    case 'hr':
                        return redirect(route('hr.dashboard'));
                    default:
                        return redirect(route('employee.dashboard'));
                }
            }
        }

        return $next($request);
    }
}
