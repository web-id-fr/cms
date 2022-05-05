<?php

namespace Webid\Cms\App\Repositories\Components;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Components\GalleryComponent;

class GalleryComponentRepository
{
    public function __construct(private GalleryComponent $model)
    {
    }

    public function getPublishedComponents(): Collection
    {
        return $this->model->all()
            ->where('status', GalleryComponent::_STATUS_PUBLISHED);
    }
}
