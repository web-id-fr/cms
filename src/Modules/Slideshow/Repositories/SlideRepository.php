<?php

namespace Webid\Cms\Modules\Slideshow\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\Slideshow\Models\Slide;

class SlideRepository
{
    /** @var Slide */
    protected $model;

    /**
     * SlideRepository constructor.
     *
     * @param Slide $model
     */
    public function __construct(Slide $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }
}
