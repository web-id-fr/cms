<?php

namespace Webid\Cms\Modules\Form\Http\Controllers;

use Webid\Cms\App\Http\Controllers\BaseController;

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
