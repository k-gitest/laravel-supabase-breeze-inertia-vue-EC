<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminRedirectIfAuthenticated;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\ErrorLogService;
use Sentry\Laravel\Integration; 

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
   
    // Sentryをグローバルハンドラに登録
    Integration::handles($exceptions);

    // Laravelであることを識別するタグを追加
    \Sentry\configureScope(function (\Sentry\State\Scope $scope) {
        $scope->setTag('platform', 'laravel');
    });

    // 既存のreport処理（ErrorLogService）はローカル開発用として維持
    $exceptions->report(function (Exception $e) {
        ErrorLogService::logError($e);
        ErrorLogService::Redirect();
    });

    // 既存のrender処理（404対応など）もローカル開発用として維持
    $exceptions->render(function (NotFoundHttpException $e) {
        ErrorLogService::logError($e);
    });

    // 既存のreport処理をクリーンアップ（ログ収集をやめる場合）
    /*
    $exceptions->report(function (Exception $e) {
        // ローカルログへの記録を削除
        // 🚨 注意: この Redirect はまだ動かない可能性がありますが、この機能を残すならここに配置
        return redirect()->back()->withErrors(['error' => 'Failed to action. Please try again.']);
    });

    // 404のレンダリングも、シンプルにLaravel標準に任せる
    // Sentryは404を自動で無視するように設定推奨
    $exceptions->render(function (NotFoundHttpException $e) {
        // Sentryは既にIntegration::handlesで通知済み
    });
    */
    
  })->create();
