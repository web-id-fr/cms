<?php

namespace Webid\Cms\Modules\Newsletter\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\Newsletter\Models\Newsletter;

class NewsletterRepository
{
    /** @var Newsletter  */
    private $model;

    /**
     * NewsletterRepository constructor.
     *
     * @param Newsletter $model
     */
    public function __construct(Newsletter $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     *
     * @return Collection<Newsletter>
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }
}
