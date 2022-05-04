<?php

namespace Webid\Cms\Modules\Galleries\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\Galleries\Models\Gallery;

class GalleryRepository
{
    public function __construct(private Gallery $model)
    {
        $this->model = $model;
    }

    public function getPublishedGalleries(): Collection
    {
        return $this->model->all()
            ->where('status', Gallery::_STATUS_PUBLISHED);
    }
}
