<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Cms\Modules\Form\Models\Recipient;

class RecipientRepository
{
    public function __construct(private Recipient $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
}
