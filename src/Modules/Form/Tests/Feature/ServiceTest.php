<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Form\Tests\FormTestCase;
use Webid\Cms\Modules\Form\Tests\Helpers\ServiceCreator;

class ServiceTest extends FormTestCase
{
    use ServiceCreator;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'services';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createService();
    }
}
