<?php

namespace Webid\Cms\Modules\Faq\Tests\Helpers;

use Webid\Cms\Modules\Faq\Models\Faq;

trait FaqCreator
{
    /**
     * @param array $parameters
     *
     * @return Faq
     */
    private function createFaq(array $parameters = []): Faq
    {
        return Faq::factory($parameters)->create();
    }
}
