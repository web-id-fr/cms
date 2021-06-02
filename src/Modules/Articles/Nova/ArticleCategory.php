<?php

namespace Webid\Cms\Modules\Articles\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\Cms\Modules\Articles\Models\ArticleCategory as ArticleCategoryModel;
use Webid\TranslatableTool\Translatable;

class ArticleCategory extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ArticleCategoryModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Categories');
    }

    /**
     * @return array|string|null
     */
    public static function singularLabel()
    {
        return __('Category');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Translatable::make(__('Name'), 'name')
                ->singleLine()
                ->rules('required'),
        ];
    }
}
