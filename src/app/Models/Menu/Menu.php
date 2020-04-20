<?php

namespace Webid\Cms\Src\App\Models\Menu;

use Webid\Cms\Src\App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    /** Le séparateur utilisé dans la clause GROUP_BY du scope withZones() */
    protected const GROUP_BY_DELIMITER = '|||';

    /** @var string */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
    ];

    /** @var $menu_items */
    public $menu_items;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function templates()
    {
        return $this->morphedByMany(Template::class, 'menuable')
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function menuCustomItems()
    {
        return $this->morphedByMany(MenuCustomItem::class, 'menuable')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function chargeMenuItems()
    {
        $menuItems = collect();
        $templates = $this->templates;
        $customItems = $this->menuCustomItems;

        $templates->each(function ($page) use (&$menuItems) {
            $page->menuable_type = Template::class;
            $menuItems->push($page);
        });
        $customItems->each(function ($customItem) use (&$menuItems) {
            $customItem->menuable_type = MenuCustomItem::class;
            $menuItems->push($customItem);
        });

        $menuItems = $menuItems->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->menu_items = $menuItems;
    }

    /**
     * Transforme l'attribut "zones" issu du scope en tableau s'il existe
     *
     * @param $zones
     *
     * @return array
     */
    public function getZonesAttribute($zones)
    {
        if (!empty($zones) && is_string($zones)) {
            $zones = explode(static::GROUP_BY_DELIMITER, $zones);
        }

        if (!empty($zones) && is_array($zones)) {
            $zones = array_filter($zones);
        }

        return $zones;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWithZones($query)
    {
        $table = $this->getTable();

        return $query->select(
            "$table.*",
            DB::raw("GROUP_CONCAT(menus_zones.zone_id SEPARATOR '" . static::GROUP_BY_DELIMITER . "') as zones")
        )->leftJoin('menus_zones', "$table.id", '=', 'menus_zones.menu_id')->groupBy("$table.id");
    }
}
