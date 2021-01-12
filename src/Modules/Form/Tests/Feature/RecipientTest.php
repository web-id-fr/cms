<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\RecipientCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class RecipientTest extends FormTestCase
{
    use RecipientCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'recipients';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createRecipient();
    }
}
