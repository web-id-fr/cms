<?php

namespace Webid\ComponentTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class ComponentTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('component-tool', __DIR__.'/../dist/js/field.js');
    }
}
