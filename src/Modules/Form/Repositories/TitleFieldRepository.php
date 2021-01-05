<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\TitleField;

class TitleFieldRepository
{
    /** @var TitleField  */
    private $model;

    /**
     * TitleFieldRepository constructor.
     *
     * @param TitleField $model
     */
    public function __construct(TitleField $model)
    {
        $this->model = $model;
    }
}
