<?php

namespace Webid\Cms\Src\Modules\Form\Repositories;

use Webid\Cms\Src\Modules\Form\Models\Service;
use Webid\Cms\Src\App\Repositories\BaseRepository;

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
