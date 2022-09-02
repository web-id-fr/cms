<?php

return [
    'disable_robots_follow' => env('DISABLE_ROBOTS_FOLLOW', false),
    'image_path' => 's3' == env('FILESYSTEM_DRIVER')
        ? 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/'
        : env('APP_URL') . "/storage/",
    'use_pagination_for_article_list' => false,
    'article_list_view' => 'components/article_list',
];
