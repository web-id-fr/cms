<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use Webid\Cms\App\Models\Newsletter\Newsletter;

trait NewsletterCreator
{
    private function createNewsletter(array $params = []): Newsletter
    {
        return Newsletter::factory()->create($params);
    }
}
