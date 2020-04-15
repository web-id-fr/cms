<?php

namespace Webid\RecipientItemField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('RecipientItemField', __DIR__.'/../dist/js/field.js');
            Nova::style('RecipientItemField', __DIR__.'/../dist/css/field.css');
        });
    }
}
