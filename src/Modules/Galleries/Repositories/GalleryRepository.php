<?php

namespace Webid\Cms\Modules\Galleries\Repositories;

use Webid\Cms\Modules\Galleries\Models\Gallery;

class GalleryRepository
{
    /** @var Gallery  */
    private $model;

    /**
     * TemplateRepository constructor.
     *
     * @param Gallery $model
     */
    public function __construct(Gallery $model)
    {
        $this->model = $model;
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
