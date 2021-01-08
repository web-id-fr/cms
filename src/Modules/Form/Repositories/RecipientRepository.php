<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Recipient;

class RecipientRepository
{
    /** @var Recipient  */
    private $model;

    /**
     * @param Recipient $model
     */
    public function __construct(Recipient $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Recipient[]
     */
    public function all()
    {
        return $this->model->all();
    }
}
