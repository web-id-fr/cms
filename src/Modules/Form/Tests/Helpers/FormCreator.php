<?php

namespace Webid\Cms\Modules\Form\Tests\Helpers;

use Webid\Cms\Modules\Form\Models\Form;

trait FormCreator
{
    /**
     * @param array $parameters
     *
     * @return Form
     */
    private function createForm(array $parameters = []): Form
    {
        return Form::factory($parameters)->create();
    }
}
