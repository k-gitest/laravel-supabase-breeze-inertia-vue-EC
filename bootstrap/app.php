<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminRedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      \App\Http\Middleware\HandleInertiaRequests::class,
      \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
      WelcomeMiddleware::class,
      AdminRedirectIfAuthenticated::class,
    ]);

    $middleware->redirectGuestsTo(function (Request $request) {

      if ($request->routeIs('admin.*')) {
        return route('admin.login');
      }

      return route('login');
    });

    $middleware->validateCsrfTokens(except: [
        'stripe/*',
    ]);
    
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();
