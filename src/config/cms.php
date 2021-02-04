<?php

return [
    //------------------------------------------
    // FOR IMAGES IN NOVA-COMPONENTS
    //------------------------------------------

    'image_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/'
        : "/",
];
