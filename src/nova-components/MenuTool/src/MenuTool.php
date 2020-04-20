<?php

namespace Webid\MenuTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class MenuTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('menu-tool', __DIR__.'/../dist/js/field.js');
        Nova::style('menu-tool', __DIR__.'/../dist/css/field.css');
    }
}
