<?php

namespace Webid\Cms\App\Http\Controllers;

use App\Http\Controllers\Controller;

class CsrfController extends Controller
{
    /**
     * @return string
     */
    public function __invoke()
    {
        return csrf_token();
    }
}
