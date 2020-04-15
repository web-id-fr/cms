<?php

namespace Webid\Cms\Src\App\Repositories\Components;

use Webid\Cms\Src\App\Models\Components\NewsletterComponent;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class NewsletterComponentRepository extends BaseRepository
{
    /**
     * Component1Repository constructor.
     *
     * @param NewsletterComponent $model
     */
    public function __construct(NewsletterComponent $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all()
    {
        return $this->model->all()
            ->where('status', NewsletterComponent::_STATUS_PUBLISHED);
    }
}
