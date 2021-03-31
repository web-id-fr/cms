<?php

namespace Webid\Cms\Tests\Helpers\Traits;

use App\Models\Template;

trait TemplateCreator
{
    private function createTemplate(array $params = []): Template
    {
        return Template::factory()->create($params);
    }

    private function createPublicTemplate(array $params = []): Template
    {
        return Template::factory()->create(array_merge(
            $params,
            [
                'status' => Template::_STATUS_PUBLISHED,
                'publish_at' => now()->subYear(),
                'indexation' => true,
            ]
        ));
    }

    private function createHomepageTemplate(array $params = []): Template
    {
        return Template::factory()->create(array_merge(
            $params,
            ['homepage' => true]
        ));
    }
}
