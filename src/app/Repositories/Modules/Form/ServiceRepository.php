<?php

namespace Webid\Cms\App\Repositories\Modules\Form;

use Webid\Cms\App\Models\Modules\Form\Service;
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
