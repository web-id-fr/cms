<?php

namespace Webid\MenuItemField;

use App\Models\Template;
use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Repositories\Menu\MenuCustomItemRepository;
use Webid\Cms\App\Repositories\TemplateRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Menu\MenuCustomItem;

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
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $menuCustomItemRepository = app()->make(MenuCustomItemRepository::class);
        $templateRepository = app()->make(TemplateRepository::class);
        $allItems = collect();
        $children = [];

        // MENU-CUSTOM-ITEM
        $allMenuCustomItems = $menuCustomItemRepository->all();
        $children = $this->getChildren($allMenuCustomItems, $children);
        $allMenuCustomItems = $this->mapItems($allMenuCustomItems, $children, MenuCustomItem::class);
        foreach ($allMenuCustomItems as $customItem) {
            $allItems->push($customItem);
        }

        // TEMPLATE
        $allTemplates = $templateRepository->getPublishedTemplates();
        $children = $this->getChildren($allTemplates, $children);
        $allTemplates = $this->mapItems($allTemplates, $children, Template::class);
        foreach ($allTemplates as $template) {
            $allItems->push($template);
        }

        $this->withMeta(['items' => $allItems]);
        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param $requestAttribute
     * @param $model
     * @param $attribute
     *
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $menuItems = json_decode($request[$requestAttribute]);
        $menuItems = collect(json_decode(json_encode($menuItems), true));

        $menuItemTemplateIds = [];
        $menuItemCustomIds = [];

        $menuItems->each(function ($menuItem, $key) use (&$menuItemTemplateIds, &$menuItemCustomIds) {
            if ($menuItem['menuable_type'] == Template::class) {
                $menuItemTemplateIds[$menuItem['id']] = [
                    'order' => $key + 1,
                    'parent_id' => null,
                    'parent_type' => null,
                ];
            } else {
                $menuItemCustomIds[$menuItem['id']] = [
                    'order' => $key + 1,
                    'parent_id' => null,
                    'parent_type' => null,
                ];
            }

            $count = 1;
            foreach ($menuItem['children'] as $children) {
                if ($children['menuable_type'] == Template::class) {
                    $menuItemTemplateIds[$children['id']] = [
                        'parent_id' => $menuItem['id'],
                        'parent_type' => $menuItem['menuable_type'],
                        'order' => $count
                    ];
                } else {
                    $menuItemCustomIds[$children['id']] = [
                        'parent_id' => $menuItem['id'],
                        'parent_type' => $menuItem['menuable_type'],
                        'order' => $count
                    ];
                }
                $count++;
            }
        });

        Menu::saved(function ($model) use ($menuItemTemplateIds, $menuItemCustomIds) {
            $model->templates()->sync($menuItemTemplateIds);
            $model->menuCustomItems()->sync($menuItemCustomIds);
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

        $valueInArray = [];
        $resource->menu_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }

    /**
     * @param Collection $items
     * @param array $children
     *
     * @return array
     */
    protected function getChildren(Collection $items, array $children): array
    {
        foreach ($items as $template) {
            foreach ($template->menus as $menu) {
                if (!empty($menu->pivot->parent_id)) {
                    $pivot = $menu->pivot;
                    $children[$pivot->menu_id][$pivot->parent_id . "-" . $pivot->parent_type][] = $template;
                }
            }
        }

        return $children;
    }

    /**
     * @param Collection $items
     * @param array $children
     * @param string $model
     *
     * @return Collection
     */
    protected function mapItems(Collection $items, array $children, string $model): Collection
    {
        return $items->map(function ($item) use ($children, $model) {
            if (!empty($children)
                && request()->route('resourceId')
                && array_key_exists(request()->route('resourceId'), $children)
                && array_key_exists($item->id . "-" . $model, $children[request()->route('resourceId')])
            ) {
                $item->children = $children[request()->route('resourceId')][$item->id . "-" . $model];
            } else {
                $item->children = [];
            }
            $item->menuable_type = $model;

            return $item;
        });
    }
}
