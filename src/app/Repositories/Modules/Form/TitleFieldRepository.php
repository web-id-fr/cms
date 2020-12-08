<?php

namespace Webid\Cms\App\Repositories\Modules\Form;

use Webid\Cms\App\Models\Modules\Form\TitleField;
use Webid\Cms\App\Repositories\BaseRepository;

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
