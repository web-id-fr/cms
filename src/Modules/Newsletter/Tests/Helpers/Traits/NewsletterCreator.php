<?php

namespace Webid\Cms\Modules\Newsletter\Tests\Helpers\Traits;

use Webid\Cms\Modules\Newsletter\Models\Newsletter;

trait NewsletterCreator
{
    /**
     * @param array $params
     *
     * @return Newsletter
     */
    private function createNewsletter(array $params = []): Newsletter
    {
        return Newsletter::factory()->create($params);
    }
}
