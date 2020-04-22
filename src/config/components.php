<?php
use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Http\Resources\Components\GalleryComponentResource;
use Webid\Cms\Src\App\Models\Components\NewsletterComponent;
use Webid\Cms\Src\App\Http\Resources\Components\NewsletterComponentResource;

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
    GalleryComponent::class => [
        'title' => 'Gallery component',
        'image' => '/cms/images/components/gallery_component.png',
        'resource' => GalleryComponentResource::class,
        'view' => 'components/galleries',
        'nova' => '/nova/resources/gallery-components'
    ],
    NewsletterComponent::class => [
        'title' => 'Newsletter component',
        'image' => '/cms/images/components/newsletter_component.png',
        'resource' => NewsletterComponentResource::class,
        'view' => 'components/newsletters',
        'nova' => '/nova/resources/newsletter-components'
    ],
];
