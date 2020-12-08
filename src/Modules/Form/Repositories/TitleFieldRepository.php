<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\TitleField;
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
