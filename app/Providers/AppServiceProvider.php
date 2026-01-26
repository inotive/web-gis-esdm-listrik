<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Permohonan;

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
        // Share userRole with sidebars (no longer need dynamic permohonan menus)
        View::composer(['admin.layouts.partials.sidebar', 'landing.layout.sidebar'], function ($view) {
            $user = auth()->user();
            $userRole = $user?->roles()->first()?->name ?? null;

            $view->with([
                'userRole' => $userRole,
            ]);
        });
    }
}
