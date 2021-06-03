<?php

namespace Webid\Cms\Modules\Slideshow\Repositories;

use Illuminate\Database\Eloquent\Model;
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
     * @return Model|null
     */
    public function find(int $id)
    {
        /** @var Model|null $slideshow */
        $slideshow = $this->model
            ->find($id);

        if ($slideshow) {
            $slideshow->with('slides');
        }

        return $slideshow;
    }
}
