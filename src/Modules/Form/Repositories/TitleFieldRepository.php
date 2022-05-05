<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Cms\Modules\Form\Models\TitleField;

class TitleFieldRepository
{
    public function __construct(private TitleField $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
}
