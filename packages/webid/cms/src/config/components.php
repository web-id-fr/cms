<?php

/*
|--------------------------------------------------------------------------
| Components config
|--------------------------------------------------------------------------
|
| Use the following schema to store components :
|
| ComponentModel::class => [
|   'title" => 'Title component",
|   'image' => 'PATH_TO_COMPONENT_IMAGE', // /images/components/component.png
|   'resource => CompenentResource::class,
|   'view' => 'PATH_TO_COMPONENT_VIEW, // components/component
|   'nova' => 'URL_TO_ACCES_RESOURCE_NOVA', // /nova/resources/component
| ]
*/

return [
    \Webid\Cms\Src\App\Models\Modules\Galleries\Gallery::class => [
        'title' => 'Gallery component',
        'image' => '/images/components/gallery_component.png',
        'resource' => Galler
    ]
];
