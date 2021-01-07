<?php

namespace Webid\Cms\App\Repositories\Modules\Slideshow;

use Webid\Cms\App\Models\Modules\Slideshow\Slide;
use Webid\Cms\App\Repositories\BaseRepository;

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
