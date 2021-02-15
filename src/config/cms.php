<?php

return [
    'image_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/'
        : env('APP_URL') . "/storage/",
];
