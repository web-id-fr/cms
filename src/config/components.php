<?php
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Http\Resources\Components\GalleryComponentResource;
use Webid\Cms\App\Models\Components\NewsletterComponent;
use Webid\Cms\App\Http\Resources\Components\NewsletterComponentResource;

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
|   'display_on_components_list' => false // optional
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
