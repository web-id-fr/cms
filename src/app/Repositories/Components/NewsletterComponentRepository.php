<?php

namespace Webid\Cms\App\Repositories\Components;

use Webid\Cms\App\Models\Components\NewsletterComponent;

class NewsletterComponentRepository
{
    /** @var NewsletterComponent */
    protected $model;
    /**
     * Component1Repository constructor.
     *
     * @param NewsletterComponent $model
     */
    public function __construct(NewsletterComponent $model)
    {
        $this->model = $model;
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
