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

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model
            ->with([
                'fields',
                'titleFields',
                'recipients',
                'services',
                'related.formables'
            ])
            ->find($id);
    }
}
