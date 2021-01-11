<?php

namespace Webid\Cms\Tests\Feature;

use Webid\Cms\Tests\Helpers\Traits\NewsletterComponentCreator;
use Webid\Cms\Tests\TestCase;

class PreviewTest extends TestCase
{
    use NewsletterComponentCreator;

    /** @var array */
    protected $data;

    const _ROUTE_INDEX = 'preview';

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            "homepage" => true,
            "title" => "titre-en-fr",
            "slug" => "slug-en-fr",
            "lang" => "fr",
            "token" => uniqid()
        ];

        $newsletterComponent = $this->createNewsletterComponent();
        $this->data['components'][] = [
            "id" => $newsletterComponent->getKey(),
            "component_type" => get_class($newsletterComponent)
        ];
    }

    /** @test */
    public function we_can_make_preview()
    {
        $this->withSession([$this->data['token'] => $this->data]);
        $this->get(route(self::_ROUTE_INDEX, $this->data))->assertSuccessful();
    }

    /** @test */
    public function we_cant_make_preview_with_wrong_token()
    {
        $this->withSession(['wrong-token' => $this->data]);
        $this->get(route(self::_ROUTE_INDEX, $this->data))->assertNotFound();
    }
}

