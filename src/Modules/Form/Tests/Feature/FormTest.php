<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\FormCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class FormTest extends FormTestCase
{
    use FormCreator, TestsNovaResource;

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
            'recipient_type' => array_search('email', config('fields_type'))
        ]);
    }
}
