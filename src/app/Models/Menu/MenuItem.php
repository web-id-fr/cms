<?php

namespace Webid\Cms\App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webid\Cms\App\Models\Contracts\Menuable;

/**
 * Class MenuItem
 *
 * @package Webid\Cms\App\Models\Menu
 *
 * @property string $menuable_type
 * @property int $menu_id
 * @property Menuable $menuable
 */
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
