<?php

namespace Webid\Cms\Modules\Form\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Webid\Cms\Modules\Form\Http\Controllers\Abstracts\FormController as BaseController;
use Webid\Cms\Modules\Form\Mail\SendConfirmationContact;

class FormController extends BaseController
{
    /**
     * @param string $email
     * @param array $extra
     *
     * @return void
     */
    protected function sendConfirmationEmail(string $email, array $extra): void
    {
        Mail::to($email)->send(new SendConfirmationContact($extra));
    }
}
