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
