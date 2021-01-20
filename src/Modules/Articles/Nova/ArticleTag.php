<?php

namespace Webid\Cms\Modules\Articles\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\Cms\Modules\Articles\Models\ArticleTag as ArticleTagModel;
use Webid\TranslatableTool\Translatable;

class ArticleTag extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ArticleTagModel::class;

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
     * @return string
     */
    public static function label()
    {
        return __('Tags');
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return __('Tag');
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
