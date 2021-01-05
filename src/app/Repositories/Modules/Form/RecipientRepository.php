<?php

namespace Webid\Cms\App\Repositories\Modules\Form;

use Webid\Cms\App\Models\Modules\Form\Recipient;
use Webid\Cms\App\Repositories\BaseRepository;

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
