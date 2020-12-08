<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Field;
use Webid\Cms\App\Repositories\BaseRepository;

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
