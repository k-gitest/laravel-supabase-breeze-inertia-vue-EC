<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Gate;
//use App\Services\SupabaseStorageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      //$this->app->bind('SbStorage', SupabaseStorageService::class);
    }

    /**
     * アプリケーションの全サービスの初期起動処理
     */
    public function boot(UrlGenerator $url): void
    {
      $url->forceScheme('https');
      $this->app['request']->server->set('HTTPS','on');
    }
}
