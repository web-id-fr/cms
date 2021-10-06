<?php

namespace Webid\Cms\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $component_type
 * @property int $component_id
 * @property int $order
 */
class Component extends Model
{
    protected $fillable = [
        'component_id',
        'component_type',
        'order',
    ];

    public function components(): MorphTo
    {
        return $this->morphTo('component');
    }
}
