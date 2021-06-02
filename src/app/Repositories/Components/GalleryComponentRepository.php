<?php

namespace Webid\Cms\App\Repositories\Components;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Components\GalleryComponent;

class GalleryComponentRepository
{
    /** @var GalleryComponent */
    protected $model;

    /**
     * @param GalleryComponent $model
     */
    public function __construct(GalleryComponent $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<GalleryComponent>
     */
    public function getPublishedComponents()
    {
        return $this->model->all()
            ->where('status', GalleryComponent::_STATUS_PUBLISHED);
    }
}
