<?php

namespace Webid\Cms\Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Models\Menu\MenuItem;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'menu_id' => null,
            'menuable_id' => null,
            'menuable_type' => null,
            'order' => random_int(1, 999),
            'parent_id' => null,
            'parent_type' => null,
        ];
    }

    /**
     * @param Template|MenuCustomItem $menuable
     * @return self
     */
    public function hasItem($menuable)
    {
        return $this->state(function () use ($menuable) {
            return [
                'menuable_id' => $menuable->getKey(),
                'menuable_type' => get_class($menuable),
            ];
        });
    }

    /**
     * @param Template|MenuCustomItem $parent
     * @return self
     */
    public function hasParent($parent)
    {
        return $this->state(function () use ($parent) {
            return [
                'parent_id' => $parent->getKey(),
                'parent_type' => get_class($parent),
            ];
        });
    }

    /**
     * @param Menu $menu
     * @return self
     */
    public function forMenu(Menu $menu)
    {
        return $this->state(function () use ($menu) {
            return [
                'menu_id' => $menu->getKey(),
            ];
        });
    }
}

