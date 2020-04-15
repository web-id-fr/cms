<?php

namespace Webid\Cms\Src\App\Repositories\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\Recipient;
use Webid\Cms\Src\App\Repositories\BaseRepository;

class RecipientRepository extends BaseRepository
{
    /**
     * FieldRepository constructor.
     *
     * @param Recipient $model
     */
    public function __construct(Recipient $model)
    {
        parent::__construct($model);
    }
}
