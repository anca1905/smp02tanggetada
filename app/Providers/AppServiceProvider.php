<?php

namespace App\Providers;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {

            $operator = Auth::guard('operator')->user();
            $teacher = Auth::guard('teacher')->user();

            $globalSettings = [];
            try {
                $globalSettings = Setting::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
            }

            $view->with([
                'operator' => $operator,
                'user' => $teacher,
                'site_settings' => $globalSettings,
            ]);
        });

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set(config('app.timezone'));
    }
}
