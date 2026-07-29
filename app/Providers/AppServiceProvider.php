<?php

namespace App\Providers;

use App\Models\HospitalSetting;
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
        $setting = HospitalSetting::first();

        view()->share('setting', $setting);
    }
}
