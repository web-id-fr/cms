<?php

namespace Webid\Cms\App\Repositories\Components;

use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Repositories\BaseRepository;

class GalleryComponentRepository extends BaseRepository
{
    /**
     * Component1Repository constructor.
     *
     * @param GalleryComponent $model
     */
    public function __construct(GalleryComponent $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getPublishedComponents()
    {
        return $this->model->all()
            ->where('status', GalleryComponent::_STATUS_PUBLISHED);
    }
}
