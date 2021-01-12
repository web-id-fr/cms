<?php

namespace Webid\Cms\Modules\Form\Tests\Helpers;

use Webid\Cms\Modules\Form\Models\Field;

trait FieldCreator
{
    /**
     * @param array $parameters
     *
     * @return Field
     */
    private function createField(array $parameters = []): Field
    {
        return Field::factory($parameters)->create();
    }
}
