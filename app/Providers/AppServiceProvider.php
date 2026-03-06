<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $appSettings = [
            'name_app' => config('app.name', 'Laravel'),
            'slug' => null,
            'image_url' => null,
        ];

        if (Schema::hasTable('app_settings')) {
            $setting = AppSetting::query()->first();

            if ($setting) {
                $appSettings = [
                    'name_app' => $setting->name_app,
                    'slug' => $setting->slug,
                    'image_url' => $setting->image_url,
                ];
            }
        }

        View::share('appSettings', $appSettings);
    }
}
