<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use App\Models\User;
use App\Models\TodoList;
use Illuminate\Support\Facades\Gate;
//use App\Services\SupabaseStorageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       // Get the AliasLoader instance 
      //$this->app->bind('SbStorage', SupabaseStorageService::class);

    }

    /**
     * アプリケーションの全サービスの初期起動処理
     */
    public function boot(UrlGenerator $url): void
    {
        //
      $url->forceScheme('https');

      Gate::define('update-todo-list', function (User $user, TodoList $todoList){
        return $user->id === $todoList->user_id;
      });      
    }
}
