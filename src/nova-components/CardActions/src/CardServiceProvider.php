<?php

namespace Webid\CardActions;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class CardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Nova::serving(function (ServingNova $event) {
            Nova::script('card-actions', __DIR__.'/../dist/js/card.js');
        });
    }
}
