<?php

namespace Webid\Cms\Src\Modules\Form\Repositories;

use Webid\Cms\Src\Modules\Form\Models\Recipient;
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
