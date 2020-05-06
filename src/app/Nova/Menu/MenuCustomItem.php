<?php

namespace Webid\Cms\Src\App\Nova\Menu;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Webid\TranslatableTool\Translatable;
use Laravel\Nova\Resource;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;

class MenuCustomItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = MenuCustomItemModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Translatable::make('Title')
                ->singleLine()
                ->rules('array'),

            Translatable::make('Url')
                ->singleLine()
                ->rules('array')
                ->hideFromIndex(),

            Select::make('Target')
                ->options(MenuCustomItemModel::STATUS_TYPE)
                ->displayUsingLabels()
                ->rules('nullable')
                ->hideFromIndex(),
        ];
    }
}
