<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Setting;
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
            $teacher  = Auth::guard('teacher')->user();

            $view->with([
                'operator' => $operator,
                'user'     => $teacher,
            ]);
        });

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        try {
            $globalSettings = Setting::pluck('value', 'key')->toArray();
            View::share('site_settings', $globalSettings);
        } catch (\Exception $e) {
        }
    }
}
