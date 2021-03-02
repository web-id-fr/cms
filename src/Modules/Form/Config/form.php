<?php

use Webid\Cms\Modules\Form\Mail\SendConfirmationContact;

return [
    'send_email_confirmation' => env('SEND_EMAIL_CONFIRMATION', false),
    'send_confirmation_contact_class' => SendConfirmationContact::class,
];
