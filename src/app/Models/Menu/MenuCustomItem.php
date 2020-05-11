<?php

namespace Webid\Cms\Src\App\Models\Menu;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Translatable\HasTranslations;
use Webid\Cms\Src\App\Models\Modules\Form\Form;

class MenuCustomItem extends Model
{
    use HasTranslations;

    const _STATUS_SELF = '_SELF';
    const _STATUS_BLANK = '_BLANK';
    const _LINK_URL = 1;
    const _LINK_FORM = 2;

    const STATUS_TYPE= [
        self::_STATUS_SELF => 'Same window',
        self::_STATUS_BLANK => 'New window',
    ];

    const TYPE_TO_LINK = [
        self::_LINK_URL => "Link url",
        self::_LINK_FORM => "Link form"
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
        'url',
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
        'type_link',
        'form_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function menus()
    {
        return $this->morphToMany(Menu::class, 'menuable')
            ->withPivot('order', 'parent_id', 'parent_type');
    }
}
