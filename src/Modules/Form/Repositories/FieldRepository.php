<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Cms\Modules\Form\Models\Field;

class FieldRepository
{
    public function __construct(private Field $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
}
