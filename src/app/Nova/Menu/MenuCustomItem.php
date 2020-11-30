<?php

namespace Webid\Cms\Src\App\Nova\Menu;

use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Webid\Cms\Src\App\Nova\Modules\Form\Form;
use Webid\TranslatableTool\Translatable;
use Laravel\Nova\Resource;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;

class MenuCustomItem extends Resource
{
    use HasDependencies;

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
            Translatable::make(__('Title'))
                ->singleLine()
                ->rules('array'),

            Select::make(__('Type link'), 'type_link')
                ->options(MenuCustomItemModel::TYPE_TO_LINK)
                ->displayUsingLabels()
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                Translatable::make(__('Url'))
                    ->singleLine()
                    ->hideFromIndex(),

                Select::make(__('Target'))
                    ->options(MenuCustomItemModel::STATUS_TYPE)
                    ->displayUsingLabels()
                    ->rules('nullable')
                    ->hideFromIndex(),
            ])->dependsOn('type_link', MenuCustomItemModel::_LINK_URL),

            NovaDependencyContainer::make([
                BelongsTo::make(__('Form'), 'form', Form::class)
                    ->nullable()
                    ->onlyOnForms(),
            ])->dependsOn('type_link', MenuCustomItemModel::_LINK_FORM),
        ];
    }
}
