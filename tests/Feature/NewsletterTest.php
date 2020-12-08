<?php

namespace Webid\Cms\Tests\Feature;

use Webid\Cms\Tests\Helpers\Traits\NewsletterCreatorTrait;
use Webid\Cms\Tests\TestCase;

class NewsletterTest extends TestCase
{
    use NewsletterCreatorTrait;

    const _NEWSLETTER_ROUTE = 'newsletter.store';

    /** @test */
    public function it_cant_store_without_email()
    {
        $this->ajaxPost(route(self::_NEWSLETTER_ROUTE, ['lang' => 'fr']))
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_cant_store_with_incorrect_email()
    {
        $this->ajaxPost(route(self::_NEWSLETTER_ROUTE, ['lang' => 'fr']), [
            'email' => 'notEmail',
        ])->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_cant_store_if_already_exist()
    {
        $this->createNewsletter(['email' => 'test@gmail.com']);

        $this->ajaxPost(route(self::_NEWSLETTER_ROUTE, ['lang' => 'fr']), [
            'email' => 'test@gmail.com',
        ])->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_store_with_correct_email()
    {
        $this->ajaxPost(
            route(self::_NEWSLETTER_ROUTE, ['lang' => 'fr']),
            ['email' => 'test@gmail.com']
        )
            ->assertSuccessful()
            ->assertJsonStructure([
                'success', 'data', 'message',
            ]);

        $this->assertDatabaseHas('newsletters', [
            'email' => 'test@gmail.com',
            'lang' => 'fr',
        ]);
    }
}
