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
use function Safe\json_decode;

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
     * @return mixed|void
     *
     * @throws \Safe\Exceptions\JsonException
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $menuItems = $request[$requestAttribute];
        $menuItems = collect(json_decode($menuItems, true));

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

    /**
     * @param Collection $items
     * @param string $model
     *
     * @return Collection
     */
    protected function mapItems(Collection $items, string $model): Collection
    {
        return $items->map(function ($item) use ($model) {
            $item->children = [];
            $item->menuable_type = $model;

            return $item;
        });
    }
}
