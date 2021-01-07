<?php

namespace Webid\Cms\App\Repositories\Modules\Form;

use Webid\Cms\App\Models\Modules\Form\Form;
use Webid\Cms\App\Repositories\BaseRepository;

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
        return $this->model
            ->where('status', Form::_STATUS_PUBLISHED)
            ->with([
                'fields',
                'titleFields',
                'recipients',
                'services',
                'related.formables'
            ])->get();
    }

    public function find(int $id)
    {
        return $this->model
            ->find($id)
            ->with([
                'fields',
                'titleFields',
                'recipients',
                'services',
                'related.formables'
            ])->first();
    }
}
