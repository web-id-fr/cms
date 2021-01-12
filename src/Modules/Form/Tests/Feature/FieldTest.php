<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\FieldCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class FieldTest extends FormTestCase
{
    use FieldCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'fields';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createField();
    }
}