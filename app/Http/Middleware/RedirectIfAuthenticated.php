<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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

                $role = Auth::user()->role;


                if ($role === 'super_admin') {
                    return redirect('/dashboard-admin');
                }

                if ($role === 'student') {
                    return redirect('/dashboard-student');
                }
                if ($role === 'admin') {
                    return redirect('/dashboard-admin');
                }
                if ($role === 'school_admin') {
                    return redirect('/dashboard-school-admin');
                }
            }
        }

        return $next($request);
    }
}
