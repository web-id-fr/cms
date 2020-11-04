<?php

namespace Webid\Cms\Src\App\Models;

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
