<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use Webid\Cms\Modules\Newsletter\Models\Newsletter;

trait NewsletterCreator
{
    private function createNewsletter(array $params = []): Newsletter
    {
        return Newsletter::factory()->create($params);
    }
}
