<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\Form;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class FormRepository extends BaseRepository
{
    /**
     * FieldRepository constructor.
     *
     * @param Form $model
     */
    public function __construct(Form $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
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

    public function find(int $id)
    {
        $model = $this->model->find($id);
        $model->chargeFieldItems();

        return $model;
    }
}
