<?php

namespace Webid\Cms\App\Models\Modules\Form;

use Illuminate\Database\Eloquent\Model;

class Formable extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function formables()
    {
        return $this->morphTo('formable');
    }
}
