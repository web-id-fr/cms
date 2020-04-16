<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Slideshow;

use Webid\Cms\Src\App\Models\Modules\Slideshow\Slide;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class SlideRepository extends BaseRepository
{
    /**
     * SlideRepository constructor.
     *
     * @param Slide $model
     */
    public function __construct(Slide $model)
    {
        parent::__construct($model);
    }
}
