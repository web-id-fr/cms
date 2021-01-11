<?php

return [

    //------------------------------------------
    // FOR MODULES GALLERIES
    //------------------------------------------

    'gallery_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? '/Galeries'
        : storage_path('app/public/Galeries/'),

    //------------------------------------------
    // FOR IMAGES IN NOVA-COMPONENTS
    //------------------------------------------

    'image_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/'
        : "/",

    'filesystem_driver' => env('FILESYSTEM_DRIVER'),

    //------------------------------------------
    // FOR SEND EMAIL CONFIRMATION
    //------------------------------------------

    'send_email_confirmation' => env('SEND_EMAIL_CONFIRMATION', false)
];
