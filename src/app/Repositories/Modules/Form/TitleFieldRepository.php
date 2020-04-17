<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\TitleField;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class TitleFieldRepository extends BaseRepository
{
    /**
     * TitleFieldRepository constructor.
     *
     * @param TitleField $model
     */
    public function __construct(TitleField $model)
    {
        parent::__construct($model);
    }
}
