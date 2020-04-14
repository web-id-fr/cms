<?php

namespace Webid\Cms\Src\App\Http\Controllers\Components;

use App\Http\Controllers\Controller;

class ComponentController extends Controller
{
    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function index()
    {
        return config('components');
    }
}
