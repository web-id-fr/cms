<?php

namespace Webid\Cms\Modules\Form\Http\Controllers;

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
