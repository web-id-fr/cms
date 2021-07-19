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

        // MENU-CUSTOM-ITEM
        $allMenuCustomItems = $menuCustomItemRepository->allWithoutChildren();
        $allMenuCustomItems = $this->mapItems($allMenuCustomItems, MenuCustomItem::class);
        foreach ($allMenuCustomItems as $customItem) {
            $allItems->push($customItem);
        }

        // TEMPLATE
        $allTemplates = $templateRepository->getPublishedTemplates();
        $allTemplates = $this->mapItems($allTemplates, Template::class);
        foreach ($allTemplates as $template) {
            $allItems->push($template);
        }

        $this->withMeta(['items' => $allItems]);
        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $menuItems = $request[$requestAttribute];
        $menuItems = collect(json_decode($menuItems, true));

        $menuItemTemplateIds = [];
        $menuItemCustomIds = [];
        $children = [];

        $menuItems->each(function ($menuItem, $key) use (&$menuItemTemplateIds, &$menuItemCustomIds, &$children) {
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
            if ($menuItem['children']) {
                $children = $this->getChildrenFor($menuItem);
            }
        });

        if (array_key_exists(Template::class, $children)) {
            $menuItemTemplateIds = array_replace_recursive($children[Template::class], $menuItemTemplateIds);
        }
        if (array_key_exists(MenuCustomItem::class, $children)) {
            $menuItemCustomIds = array_replace_recursive($children[MenuCustomItem::class], $menuItemCustomIds);
        }

        Menu::saved(function ($model) use ($menuItemTemplateIds, $menuItemCustomIds) {
            $model->templates()->sync($menuItemTemplateIds);
            $model->menuCustomItems()->sync($menuItemCustomIds);
        });
    }

    /**
     * @param mixed $resource
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

    private function mapItems(Collection $items, string $model): Collection
    {
        return $items->map(function ($item) use ($model) {
            $item->children = [];
            $item->menuable_type = $model;

            return $item;
        });
    }

    private function getChildrenFor(array $menuItem): array
    {
        $AllChildren = [];
        $count = 1;

        foreach ($menuItem['children'] as $children) {
            $AllChildren[$children['menuable_type']][$children['id']] =  [
                'parent_id' => $menuItem['id'],
                'parent_type' => $menuItem['menuable_type'],
                'order' => $count
            ];

            if (count($children['children']) > 0) {
                $AllChildren = array_replace_recursive($AllChildren, $this->getChildrenFor($children));
            }
            $count++;
        }

        return $AllChildren;
    }
}
