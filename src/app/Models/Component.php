<?php

namespace Webid\Cms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Component extends Model
{
    public function components(): MorphTo
    {
        return $this->morphTo('component');
    }
}
