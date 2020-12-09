<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use App\Models\Template;

trait TemplateCreator
{
    private function createTemplate(array $params = []): Template
    {
        return Template::factory()->create($params);
    }

    private function createHomepageTemplate(array $params = []): Template
    {
        return Template::factory()->create(array_merge(
            $params,
            ['homepage' => true]
        ));
    }
}
