<?php

use App\Http\Middleware\EnsureUserHasActiveSubscription;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'subscribed' => EnsureUserHasActiveSubscription::class
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\ApplyUserPreferences::class,
            \App\Http\Middleware\MarkNotificationAsRead::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'payments/stripe/webhook',
        ]);
    })
    ->withProviders([
        App\Providers\PaymentServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
