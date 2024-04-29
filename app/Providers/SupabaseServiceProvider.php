<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SupabaseStorageService;

class SupabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
      $this->app->bind('SbStorage', function(){
        return new SupabaseStorageService();
      });

      $this->app->bind('SB', function(){
        return new SupabaseStorageService();
      });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
