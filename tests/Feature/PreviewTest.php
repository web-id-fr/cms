<?php

namespace Webid\Cms\Tests\Feature;

use Webid\Cms\Tests\Helpers\Traits\NewsletterComponentCreator;
use Webid\Cms\Tests\TestCase;

class PreviewTest extends TestCase
{
    use NewsletterComponentCreator;

    const _ROUTE_INDEX = 'preview';

    /** @test */
    public function we_can_make_preview()
    {
        $data = [
            "homepage" => true,
            "title" => "titre-en-fr",
            "slug" => "slug-en-fr",
            "lang" => "fr",
            "token" => uniqid()
        ];

        $newsletterComponent = $this->createNewsletterComponent();
        $data['components'][] = [
            "id" => $newsletterComponent->getKey(),
            "component_type" => get_class($newsletterComponent)
        ];
        $this->withSession([$data['token'] => $data]);
        $this->get(route(self::_ROUTE_INDEX, $data))->assertSuccessful();
    }
}

