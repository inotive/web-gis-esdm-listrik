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
        // Share userRole, shouldShowMenu, and permohonans with both admin and landing sidebars
        View::composer(['admin.layouts.partials.sidebar', 'landing.layout.sidebar'], function ($view) {
            $user = auth()->user();
            $userRole = $user?->roles()->first()?->name ?? null;

            $permohonans = collect();
            $shouldShowMenu = false;

            // if ($userRole == 'superadmin' || $userRole == 'admin') {
            //     $permohonans = Permohonan::all();
            //     $shouldShowMenu = $permohonans->count() > 0;
            // } else
            if (in_array($userRole, ['desa', 'perusahaan'])) {
                $permohonans = Permohonan::where('jenis_permohonan', $userRole)->get();
                $shouldShowMenu = $permohonans->count() > 0;
            }

            $view->with([
                'shouldShowMenu' => $shouldShowMenu,
                'permohonans' => $permohonans,
                'userRole' => $userRole,
            ]);
        });
    }
}
