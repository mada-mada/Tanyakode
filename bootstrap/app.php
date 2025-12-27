<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/resource.php'));
        },
    )
   ->withMiddleware(function (Middleware $middleware) {


        $middleware->redirectUsersTo(function () {

            $user = Auth::user();


            if ($user->role === 'super_admin') {
                return '/superadmin/dashboard';
            }

            if ($user->role === 'student') {
                return '/student/dashboard'; //
            }

            return '/home';
        });


        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
