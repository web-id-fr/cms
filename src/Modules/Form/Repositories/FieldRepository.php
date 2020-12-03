<?php

namespace Webid\Cms\Src\Modules\Form\Repositories;

use Webid\Cms\Src\Modules\Form\Models\Field;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class FieldRepository extends BaseRepository
{
    /**
     * FieldRepository constructor.
     *
     * @param Field $model
     */
    public function __construct(Field $model)
    {
        parent::__construct($model);
    }
}
