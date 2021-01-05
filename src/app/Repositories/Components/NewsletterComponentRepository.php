<?php

namespace Webid\Cms\App\Repositories\Components;

use Webid\Cms\App\Models\Components\NewsletterComponent;
use Webid\Cms\App\Repositories\BaseRepository;

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
    public function getPublishedComponents()
    {
        return $this->model->all()
            ->where('status', NewsletterComponent::_STATUS_PUBLISHED);
    }
}
