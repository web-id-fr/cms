<?php

namespace Webid\Cms\Modules\Faq\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\Cms\Modules\Faq\Models\FaqTheme as FaqThemeModel;
use Laravel\Nova\Fields\Select;
use Webid\TranslatableTool\Translatable;

class FaqTheme extends Resource
{
    /** @var FaqThemeModel */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FaqThemeModel::class;

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
        'id',
        'title',
    ];

    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Faq Themes');
    }

    /**
     * @return array|string|null
     */
    public static function singularLabel()
    {
        return __('Faq Theme');
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
            ID::make()->sortable(),

            Translatable::make(__('Title'), 'title')
                ->rules('required')
                ->singleLine(),

            Select::make(__('Status'), 'status')
                ->options(FaqThemeModel::statusLabels())
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->sortable(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->resource->status == FaqThemeModel::_STATUS_PUBLISHED;
    }
}
