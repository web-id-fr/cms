<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\TitleField;

class TitleFieldRepository
{
    /** @var TitleField  */
    private $model;

    /**
     * @param TitleField $model
     */
    public function __construct(TitleField $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|TitleField[]
     */
    public function all()
    {
        return $this->model->all();
    }
}
