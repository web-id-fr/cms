<?php

namespace Webid\Cms\Modules\Form\Tests\Helpers;

use Webid\Cms\Modules\Form\Models\Service;

trait ServiceCreator
{
    /**
     * @param array $parameters
     *
     * @return Service
     */
    private function createService(array $parameters = []): Service
    {
        return Service::factory($parameters)->create();
    }
}
