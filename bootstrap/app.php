<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminRedirectIfAuthenticated;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\ErrorLogService;

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
   
    $exceptions->report(function (Exception $e) {
        ErrorLogService::logError($e);
        ErrorLogService::Redirect();
    });

    $exceptions->render(function (NotFoundHttpException $e) {
        ErrorLogService::logError($e);
    });

    
  })->create();
