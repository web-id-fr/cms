<?php

namespace Webid\MenuItemField;

use App\Models\Modules\Menu;
use App\Repositories\Modules\MenuCustomItemRepository;
use App\Repositories\TemplateRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MenuItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'MenuItemField';

    /**
     * @var $relationModel
     */
    public $relationModel;

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

        $allItem = $menuCustomItemRepository->all();
        $allItem->map(function ($customItem) {
            $customItem->menuable_type = 'App\Models\Models\MenuCustomItem';
            $customItem->children = [];
            return $customItem;
        });
        $allTemplate = $templateRepository->all();
        $allTemplate->map(function ($template) {
            $template->menuable_type = 'App\Models\Template';
            $template->children = [];
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
            if ($menuItem['menuable_type'] == 'App\Models\Template') {
                $menuItemTemplateIds[$menuItem['id']] = ['order' => $key + 1];
            } else {
                $menuItemCustomIds[$menuItem['id']] = ['order' => $key + 1];
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
