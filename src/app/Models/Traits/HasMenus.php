<?php

namespace Webid\Cms\App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuItem;

trait HasMenus
{
    public function menus(): MorphToMany
    {
        return $this->morphToMany(Menu::class, 'menuable')
            ->with('children')
            ->withPivot('order', 'parent_id', 'parent_type');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('parent_type', static::class)
            ->with('menus')
            ->orderBy('order');
    }

    public function childrenForMenu(int $menu_id): Collection
    {
        return $this->children()->where('menu_id', $menu_id)->get();
    }
}
