<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDriver; // ✅ Import IsDriver
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RolePageAccess; // ✅ Import your middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ Register middleware aliases
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            'is_driver' => IsDriver::class, // ✅ Added here

        'role_page' => RolePageAccess::class, // ✅ Register it here
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
