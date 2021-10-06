<?php

namespace Webid\Cms\App\Providers;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!$this->app->environment('testing')) {
            return;
        }

        $this->loadMigrationsFrom(package_base_path('vendor/orchestra/testbench-core/laravel/migrations'));
        $this->loadMigrationsFrom(package_base_path('src/Modules/*/Database/Migrations'));
        $this->loadMigrationsFrom(package_base_path('tests/Database/Migrations'));

        if (!is_dir(app_path('Nova'))) {
            mkdir(app_path('Nova'));
        }
    }
}
