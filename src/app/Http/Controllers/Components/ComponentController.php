<?php

namespace Webid\Cms\App\Http\Controllers\Components;

use Webid\Cms\App\Http\Controllers\BaseController;

class ComponentController extends BaseController
{
    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function index()
    {
        $components = config('components');

        $filter_components = array_filter($components, function ($component) {
            return (!array_key_exists('display_on_component_list', $component)
                || $component['display_on_component_list'] !== false);
        });

        return $filter_components;
    }
}
