<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use Webid\Cms\App\Models\Components\NewsletterComponent;

trait NewsletterComponentCreator
{
    private function createNewsletterComponent(array $params = []): NewsletterComponent
    {
        return NewsletterComponent::factory()->create($params);
    }
}
