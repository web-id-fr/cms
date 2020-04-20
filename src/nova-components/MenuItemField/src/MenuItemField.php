<?php

namespace Webid\MenuItemField;

use Webid\Cms\Src\App\Models\Menu\Menu;
use Webid\Cms\Src\App\Repositories\Menu\MenuCustomItemRepository;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Src\App\Models\Template;

class MenuItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'MenuItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        $menuCustomItemRepository = app()->make(MenuCustomItemRepository::class);
        $templateRepository = app()->make(TemplateRepository::class);
        $children = [];

        // MENU-CUSTOM-ITEM
        $allMenuCustomItem = $menuCustomItemRepository->all();
        $children = $this->getChildren($allMenuCustomItem, $children);
        $allItem = $this->mapItems($allMenuCustomItem, $children, MenuCustomItem::class);

        // TEMPLATE
        $allTemplate = $templateRepository->all();
        $children = $this->getChildren($allTemplate, $children);
        $allTemplate = $this->mapItems($allTemplate, $children, Template::class);

        $allTemplate->each(function ($template) use (&$allMenuCustomItem) {
            $allMenuCustomItem->push($template);
        });

        $this->withMeta(['items' => $allItem]);
        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param $requestAttribute
     * @param $model
     * @param $attribute
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $menuItems = json_decode($request[$requestAttribute]);
        $menuItems = collect(json_decode(json_encode($menuItems), true));

        $menuItemTemplateIds = [];
        $menuItemCustomIds = [];

        $menuItems->each(function ($menuItem, $key) use (&$menuItemTemplateIds, &$menuItemCustomIds) {
            if ($menuItem['menuable_type'] == Template::class) {
                $menuItemTemplateIds[$menuItem['id']] = ['order' => $key + 1];
            } else {
                $menuItemCustomIds[$menuItem['id']] = ['order' => $key + 1];
            }

            $count = 1;
            foreach ($menuItem['children'] as $children) {
                if ($children['menuable_type'] == Template::class) {
                    $menuItemTemplateIds[$children['id']] = [
                        'parent_id' => $menuItem['id'],
                        'order' => $count
                    ];
                } else {
                    $menuItemCustomIds[$menuItem['id']] = [
                        'parent_id' => $menuItem['id'],
                        'order' => $count
                    ];
                }
                $count++;
            }
        });

        Menu::saved(function ($model) use ($menuItemTemplateIds, $menuItemCustomIds) {
            $model->templates()->sync($menuItemTemplateIds);
            $model->menu_custom_items()->sync($menuItemCustomIds);
        });
    }

    /**
     * @param $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->chargeMenuItems();

        $valueInArray = array();
        $resource->menu_items->each(function($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }

    /**
     * @param $items
     * @param $children
     *
     * @return mixed
     */
    protected function getChildren($items, $children)
    {
        foreach ($items as $template) {
            foreach ($template->menus as $menu) {
                if(!empty($menu->pivot->parent_id)) {
                    $children[$menu->pivot->menu_id][$menu->pivot->parent_id][] = $template;
                }
            }
        }

        return $children;
    }

    /**
     * @param $items
     * @param $children
     * @param $model
     *
     * @return mixed
     */
    protected function mapItems($items, $children, $model)
    {
        return $items->map(function ($item) use ($children, $model) {
            if(request()->route('resourceId') && array_key_exists($item->id, $children[request()->route('resourceId')])){
                $item->children = $children[request()->route('resourceId')][$item->id];
            } else {
                $item->children = [];
            }
            $item->menuable_type = $model;

            return $item;
        });
    }
}
