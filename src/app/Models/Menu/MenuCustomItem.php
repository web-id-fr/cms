<?php

namespace Webid\Cms\Src\App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Translatable\HasTranslations;

class MenuCustomItem extends Model
{
    use HasTranslations;

    const _STATUS_SELF = '_SELF';
    const _STATUS_BLANK = '_BLANK';

    const STATUS_TYPE= [
        self::_STATUS_SELF => 'Same window',
        self::_STATUS_BLANK => 'New window',
    ];

    /** @var string  */
    protected $table = 'menu_custom_items';

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'url'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'target',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function menus()
    {
        return $this->morphToMany(Menu::class, 'menuable')
            ->withPivot('order', 'parent_id');
    }
}
