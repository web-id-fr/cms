<?php

namespace Webid\Cms\App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menuables';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function menus()
    {
        return $this->morphTo('menuable');
    }
}
