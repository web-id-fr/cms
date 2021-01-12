<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\TitleFieldCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class TitleFieldTest extends FormTestCase
{
    use TitleFieldCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'title-fields';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createTitleField();
    }
}