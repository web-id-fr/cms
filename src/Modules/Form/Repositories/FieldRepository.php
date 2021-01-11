<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Field;

class FieldRepository
{
    /** @var Field  */
    private $model;

    /**
     * @param Field $model
     */
    public function __construct(Field $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Field[]
     */
    public function all()
    {
        return $this->model->all();
    }
}
