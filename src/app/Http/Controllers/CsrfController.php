<?php

namespace Webid\Cms\App\Http\Controllers;

class CsrfController extends BaseController
{
    /**
     * @return string
     */
    public function __invoke()
    {
        return csrf_token();
    }
}
