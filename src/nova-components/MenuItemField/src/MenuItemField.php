<?php

namespace Webid\MenuItemField;

use App\Models\Template;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Repositories\Menu\MenuCustomItemRepository;
use Webid\Cms\App\Repositories\TemplateRepository;

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
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $menuItemsCollection = $request[$requestAttribute];
        $menuItemsCollection = collect(json_decode($menuItemsCollection, true));

        /** @var array<PlainMenuItem> $plainItemsList */
        $plainItemsList = [];
        $order = 1;

        foreach ($menuItemsCollection as $menuItem) {
            $plainItemsList[] = new PlainMenuItem(
                $menuItem['id'],
                $menuItem['menuable_type'],
                $order++,
                null,
                null,
                $this->getChildrenFor($menuItem)
            );
        }

        [$templatesToSync, $customItemsToSync] = $this->getItemsToSyncFromPlainObjects($plainItemsList);

        Menu::saved(function (Menu $model) use ($customItemsToSync, $templatesToSync) {
            $model->templates()->sync($templatesToSync);
            $model->menuCustomItems()->sync($customItemsToSync);
        });
    }

    /**
     * @param Menu $resource
     * @param string|null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        $resource->chargeMenuItems();

        $valueInArray = [];
        foreach ($resource->menu_items as $item) {
            $valueInArray[] = $item;
        }

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
        $allChildren = [];
        $order = 1;

        foreach ($menuItem['children'] as $children) {
            $allChildren[] = new PlainMenuItem(
                $children['id'],
                $children['menuable_type'],
                $order++,
                $menuItem['id'],
                $menuItem['menuable_type']
            );

            if (count($children['children']) > 0) {
                $allChildren = array_merge($allChildren, $this->getChildrenFor($children));
            }
        }

        // todo dédoublonner avec la méthode Collection::unique() ?
        # https://laravel.com/docs/8.x/collections#method-unique

        return $allChildren;
    }

    /**
     * @return array<int, array>
     */
    private function getItemsToSyncFromPlainObjects(array $plainItemsList): array
    {
        $templatesToSync = [];
        $customItemsToSync = [];

        foreach ($plainItemsList as $plainItem) {
            $dataToSync = [
                'order' => $plainItem->order,
                'parent_id' => $plainItem->parentId,
                'parent_type' => $plainItem->parentType,
            ];

            if (Template::class == $plainItem->menuableType) {
                $templatesToSync[$plainItem->menuableId] = $dataToSync;
            } else {
                $customItemsToSync[$plainItem->menuableId] = $dataToSync;
            }

            if (!empty($plainItem->children)) {
                [$childrenTemplatesToSync, $childrenCustomItemsToSync] = $this->getItemsToSyncFromPlainObjects(
                    $plainItem->children
                );

                $templatesToSync = $childrenTemplatesToSync + $templatesToSync;
                $customItemsToSync = $childrenCustomItemsToSync + $customItemsToSync;
            }
        }

        return [
            $templatesToSync,
            $customItemsToSync,
        ];
    }
}
