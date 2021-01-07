<?php

namespace Webid\Cms\App\Nova\Menu;

use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Webid\Cms\App\Nova\Modules\Form\Form;
use Webid\TranslatableTool\Translatable;
use Laravel\Nova\Resource;
use Webid\Cms\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Menu custom items');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('array'),

            Select::make(__('Type link'), 'type_link')
                ->options(MenuCustomItemModel::linksTypes())
                ->displayUsingLabels()
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                Translatable::make(__('Url'), 'url')
                    ->singleLine()
                    ->hideFromIndex(),

                Select::make(__('Target'), 'target')
                    ->options(MenuCustomItemModel::statusTypes())
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
