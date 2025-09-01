<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\RolePage;
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
    
     public function boot()
{
    View::composer('*', function ($view) {
        $user = Auth::user();
        $allowedRoutes = [];

        if ($user) {
            $allowedRoutes = RolePage::where('role_id', $user->role_id)
                                      ->pluck('page_name')
                                      ->toArray();
        }

        $view->with('allowedRoutes', $allowedRoutes);
    });
}
}
