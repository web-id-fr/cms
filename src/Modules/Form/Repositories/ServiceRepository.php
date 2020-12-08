<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Service;
use Webid\Cms\App\Repositories\BaseRepository;

class ServiceRepository extends BaseRepository
{
    /**
     * FieldRepository constructor.
     *
     * @param Service $model
     */
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }
}
