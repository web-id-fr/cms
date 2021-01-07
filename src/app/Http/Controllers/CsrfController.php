<?php

namespace Webid\Cms\App\Http\Controllers;

class CsrfController extends BaseController
{
    /**
     * @return string
     */
    public function index()
    {
        return csrf_token();
    }
}
