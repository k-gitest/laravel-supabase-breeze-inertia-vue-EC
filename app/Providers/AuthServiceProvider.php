<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\Admin;
use Log;

class AuthServiceProvider extends ServiceProvider
{    
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('isGeneral', function (User $user): Response
        {
            if($user?->role === "general"){
                return Response::allow();
            } else {
                Log::error('Failed to authorize. Not General user: ' . request()->ip());
                return Response::denyAsNotFound('Not General user.');
            }
        });

        Gate::define('isAdmin', function (): Response
        {
            $admin = Auth::guard('admin')->user();
            
            if($admin?->role === "admin"){
                return Response::allow();
            } else {
                Log::error('Failed to authorize. Not Admin user: ' . request()->ip());
                return Response::denyAsNotFound('Not Admin user.');
            }
        });

    }
}
