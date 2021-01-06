<?php

namespace Webid\Cms\App\Repositories\Modules\Galleries;

use Webid\Cms\App\Models\Modules\Galleries\Gallery;
use Webid\Cms\App\Repositories\BaseRepository;

class GalleryRepository extends BaseRepository
{
    /**
     * TemplateRepository constructor.
     *
     * @param Gallery $model
     */
    public function __construct(Gallery $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getPublishedGalleries()
    {
        return $this->model->all()
            ->where('status', Gallery::_STATUS_PUBLISHED);
    }
}
