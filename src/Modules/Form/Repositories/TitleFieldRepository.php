<?php

namespace Webid\Cms\Src\Modules\Form\Repositories;

use Webid\Cms\Src\Modules\Form\Models\TitleField;
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
