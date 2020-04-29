<?php

return [
    'template_model' => \Webid\Cms\Src\App\Models\Template::class,
    // FOR MODULES GALLERIES
    'gallery_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '/' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/Galeries'
        : storage_path('app/public/Galeries/'),
    // FOR IMAGES IN NOVA-COMPONENTS
    'image_path' =>'s3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '/' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/'
        : "/",
];
