<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\FormCreator;

class FormTest extends FormTestCase
{
    use FormCreator;

    const _FORM_ROUTE = 'send.form';

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'forms';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createForm([
            'recipient_type' => 2
        ]);
    }
}
