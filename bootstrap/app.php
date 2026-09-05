<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\CheckAppMaintenance::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->alias([
            'admin.konsolidator' => \App\Http\Middleware\IsAdminOrKonsolidator::class,
            'role.bank' => \App\Http\Middleware\RoleBank::class,
            'role.inspektorat' => \App\Http\Middleware\RoleInspektorat::class,
            'role.operator' => \App\Http\Middleware\RoleOperatorOrAdmin::class,
            'role.konsolidator' => \App\Http\Middleware\RoleKonsolidator::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
