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

        $allItem = $menuCustomItemRepository->all();
        foreach ($allItem as $template) {
            foreach ($template->menus as $menu) {
                if(!empty($menu->pivot->parent_id)) {
                    $children[$menu->pivot->menu_id][$menu->pivot->parent_id][] = $template;
                }
            }
        }
        $allItem->map(function ($customItem) use ($children) {
            if(request()->route('resourceId') && array_key_exists($customItem->id, $children[request()->route('resourceId')])){
                $customItem->children = $children[request()->route('resourceId')][$customItem->id];
            } else {
                $customItem->children = [];
            }
            $customItem->menuable_type = MenuCustomItem::class;
            return $customItem;
        });

        $allTemplate = $templateRepository->all();
        foreach ($allTemplate as $template) {
            foreach ($template->menus as $menu) {
                if(!empty($menu->pivot->parent_id)) {
                    $children[$menu->pivot->menu_id][$menu->pivot->parent_id][] = $template;
                }
            }
        }
        $allTemplate->map(function ($template) use ($children) {
            if(request()->route('resourceId') && array_key_exists($template->id, $children[request()->route('resourceId')])){
                $template->children = $children[request()->route('resourceId')][$template->id];
            } else {
                $template->children = [];
            }
            $template->menuable_type = Template::class;
            return $template;
        });

        $allTemplate->each(function ($template) use (&$allItem) {
            $allItem->push($template);
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
}
