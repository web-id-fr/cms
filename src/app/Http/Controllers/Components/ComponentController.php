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
        foreach ($components as $key => $value) {
            if (array_key_exists('display_on_component_list', $value)
                && $value['display_on_component_list'] === false) {
                unset($components[$key]);
            }
        }
        return $components;
    }
}
