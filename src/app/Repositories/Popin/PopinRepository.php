<?php

namespace Webid\Cms\App\Repositories\Popin;

use Webid\Cms\App\Models\Popin\Popin;

class PopinRepository
{
    /** @var Popin */
    protected $model;

    /**
     * PopinRepository constructor.
     *
     * @param Popin $model
     */
    public function __construct(Popin $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getPublishedPopins()
    {
        return $this->model->all()
            ->where('status', Popin::_STATUS_PUBLISHED);
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function findByPageId(int $id)
    {
        return $this->model
            ->whereHas('templates', function ($query) use ($id) {
                $query->where('template_id', '=', $id);
            })
            ->where('status', '=', Popin::_STATUS_PUBLISHED)
            ->get();
    }
}
