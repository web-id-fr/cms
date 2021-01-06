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
        return config('components');
    }
}
