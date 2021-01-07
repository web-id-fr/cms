<?php

namespace Webid\Cms\Modules\Newsletter\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Newsletter\Tests\Helpers\Traits\NewsletterCreator;
use Webid\Cms\Modules\Newsletter\Tests\NewsletterTestCase;

class NewsletterTest extends NewsletterTestCase
{
    use NewsletterCreator;

    const _NEWSLETTER_ROUTE = 'newsletter.store';

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'newsletters';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createNewsletter();
    }

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
