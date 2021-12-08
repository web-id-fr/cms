<?php

namespace Webid\Cms\App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Contracts\Menuable;
use Webid\Cms\App\Models\Traits\HasMenus;
use Webid\Cms\Modules\Form\Models\Form;

/**
 * Class MenuCustomItem
 *
 * @package Webid\Cms\App\Models\Menu
 *
 * @property int $id
 * @property string $title
 * @property int $type_link
 * @property Form|null $form
 * @property string|null $url
 * @property string $target
 * @property string $slug
 */
class MenuCustomItem extends Model implements Menuable
{
    use HasTranslations, HasFactory, HasMenus;

    const _STATUS_SELF = '_SELF';
    const _STATUS_BLANK = '_BLANK';
    const _LINK_URL = 1;
    const _LINK_FORM = 2;

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
        'menu_description',
    ];

    protected $fillable = [
        'title',
        'url',
        'target',
        'type_link',
        'form_id',
        'menu_description',
    ];

    /**
     * @return array
     */
    public static function statusTypes(): array
    {
        return [
            self::_STATUS_SELF => __('Same window'),
            self::_STATUS_BLANK => __('New window'),
        ];
    }

    /**
     * @return array
     */
    public static function linksTypes(): array
    {
        return [
            self::_LINK_URL => __("Link url"),
            self::_LINK_FORM => __("Link form"),
        ];
    }

    /**
     * @return BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
