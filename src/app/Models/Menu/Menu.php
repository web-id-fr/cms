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
        $customItems = $this->menu_custom_items;
        $children = [];

        foreach ($templates as $template) {
            foreach ($template->menus as $menu) {
                if (!empty($menu->pivot->parent_id)) {
                    $children[$menu->pivot->menu_id][$menu->pivot->parent_id][] = $template;
                }
            }
        }

        $templates->map(function ($template) use ($children, &$menuItems) {
            if (array_key_exists($template->id, $children[$template->getOriginal('pivot_menu_id')])) {
                $template->children = $children[$template->getOriginal('pivot_menu_id')][$template->id];
            } else {
                $template->children = [];
            }
            $template->menuable_type = Template::class;
            $menuItems->push($template);
        });
        $customItems->each(function ($customItem) use ($children, &$menuItems) {
            if (array_key_exists($customItem->id, $children[$customItem->getOriginal('pivot_menu_id')])) {
                $customItem->children = $children[$customItem->getOriginal('pivot_menu_id')][$customItem->id];
            } else {
                $customItem->children = [];
            }
            $customItem->menuable_type = MenuCustomItem::class;
            $menuItems->push($customItem);
        });

        $menuItems = $menuItems->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $filteredItems = $menuItems->reject(function ($value, $key) {
            if (!empty($value->pivot->parent_id)) {
                return true;
            }
            return false;
        });

        $this->menu_items = $filteredItems;
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
