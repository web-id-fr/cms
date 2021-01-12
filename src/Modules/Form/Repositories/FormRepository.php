<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Form;

class FormRepository
{
    /** @var Form  */
    private $model;

    /**
     * @param Form $model
     */
    public function __construct(Form $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getPublishedForms()
    {
        $models = $this->model
            ->where('status', Form::_STATUS_PUBLISHED)
            ->get();

        $models->each(function ($model) {
            $model->chargeFieldItems();
        });

        return $models;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        $model = $this->model->find($id);
        $model->chargeFieldItems();

        return $model;
    }
}
