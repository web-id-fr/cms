<?php

namespace Webid\Cms\App\Repositories\Newsletter;

use Webid\Cms\App\Models\Newsletter\Newsletter;
use Webid\Cms\App\Repositories\BaseRepository;

class NewsletterRepository extends BaseRepository
{
    /**
     * NewsletterRepository constructor.
     *
     * @param Newsletter $model
     */
    public function __construct(Newsletter $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     * @return boolean
     */
    public function store(Array $data)
    {
        return $this->model->create($data);
    }
}
