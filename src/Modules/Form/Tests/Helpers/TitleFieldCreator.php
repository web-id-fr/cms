<?php

namespace Webid\Cms\Modules\Form\Tests\Helpers;

use Webid\Cms\Modules\Form\Models\TitleField;

trait TitleFieldCreator
{
    /**
     * @param array $parameters
     *
     * @return TitleField
     */
    private function createTitleField(array $parameters = []): TitleField
    {
        return TitleField::factory($parameters)->create();
    }
}
