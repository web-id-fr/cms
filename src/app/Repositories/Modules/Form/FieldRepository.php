<?php

namespace Webid\Cms\App\Repositories\Modules\Form;

use Webid\Cms\App\Models\Modules\Form\Field;
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
