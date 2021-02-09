<?php

namespace Webid\Cms\Modules\Slideshow\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\Slideshow\Models\Slideshow;

class SlideshowRepository
{
    /** @var Slideshow */
    protected $model;

    /**
     * SlideshowRepository constructor.
     *
     * @param Slideshow $model
     */
    public function __construct(Slideshow $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model
            ->with('slides')
            ->get();
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model
            ->find($id)
            ->with('slides')
            ->first();
    }
}
