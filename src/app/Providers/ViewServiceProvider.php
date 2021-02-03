<?php

namespace Webid\Cms\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (!request()->is('nova*')) {
                $currentLangKey = request()->lang ?? config('app.locale');
                $currentLang = config("translatable.locales.{$currentLangKey}");

                View::share('currentLang', $currentLang);
                View::share('currentLangKey', $currentLangKey);
            }
        });
    }
}
