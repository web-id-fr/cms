<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Slideshow;

use Webid\Cms\Src\App\Repositories\BaseRepository;
use Webid\Cms\Src\App\Models\Modules\Slideshow\Slideshow;
use Illuminate\Support\Collection;

class SlideshowRepository extends BaseRepository
{
    /**
     * SlideshowRepository constructor.
     *
     * @param Slideshow $model
     */
    public function __construct(Slideshow $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        $models = $this->model->all();

        $models->each(function ($model) {
            $model->chargeSlideItems();
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
        $model->chargeSlideItems();

        return $model;
    }
}
