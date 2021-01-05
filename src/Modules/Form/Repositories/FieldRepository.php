<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Field;

class FieldRepository
{
    /** @var Field  */
    private $model;

    /**
     * FieldRepository constructor.
     *
     * @param Field $model
     */
    public function __construct(Field $model)
    {
        $this->model = $model;
    }
}
