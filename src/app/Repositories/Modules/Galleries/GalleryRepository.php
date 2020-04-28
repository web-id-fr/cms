<?php

namespace Webid\Cms\Src\App\Repositories;

use Webid\Cms\Src\App\Models\Modules\Galleries\Gallery;

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
