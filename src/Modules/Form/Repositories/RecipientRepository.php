<?php

namespace Webid\Cms\Modules\Form\Repositories;

use Webid\Cms\Modules\Form\Models\Recipient;
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
