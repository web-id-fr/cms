<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Service;

class ServiceRepository
{
    /** @var Service  */
    private $model;

    /**
     * @param Service $model
     */
    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }
}
