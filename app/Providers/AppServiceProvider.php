<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('hasrole', function (...$roles) {
            if (!session()->has('roles')) {
                return false;
            }
            $userRoles = session('roles');
            foreach ($roles as $role) {
                if (in_array($role, $userRoles)) {
                    return true;
                }
            }

            return false;
        });

    }
}
