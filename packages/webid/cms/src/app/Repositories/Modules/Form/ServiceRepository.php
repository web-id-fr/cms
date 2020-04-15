<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\Service;
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
