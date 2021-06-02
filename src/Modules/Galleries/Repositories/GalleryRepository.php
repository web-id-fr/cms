<?php

namespace Webid\Cms\Modules\Galleries\Repositories;

use Illuminate\Support\Collection;
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
     * @return Collection<Gallery>
     */
    public function getPublishedGalleries()
    {
        return $this->model->all()
            ->where('status', Gallery::_STATUS_PUBLISHED);
    }
}
