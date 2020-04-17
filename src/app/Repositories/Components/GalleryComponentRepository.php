<?php

namespace Webid\Cms\Src\App\Repositories\Components;

use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Repositories\BaseRepository;

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
    public function all()
    {
        return $this->model->all()
            ->where('status', GalleryComponent::_STATUS_PUBLISHED);
    }
}
