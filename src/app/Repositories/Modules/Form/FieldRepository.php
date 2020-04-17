<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\Field;
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
