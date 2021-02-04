<?php

namespace Webid\Cms\Modules\Faq\Tests\Helpers;

use Webid\Cms\Modules\Faq\Models\FaqTheme;

trait FaqThemeCreator
{
    /**
     * @param array $parameters
     *
     * @return FaqTheme
     */
    private function createFaqTheme(array $parameters = []): FaqTheme
    {
        return FaqTheme::factory($parameters)->create();
    }
}
