<?php

namespace Webid\Cms\App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function components()
    {
        return $this->morphTo('component');
    }
}
