<?php

namespace Webid\Cms\Modules\Form\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Modules\Form\Models\TitleField as TitleFieldModel;

class TitleField extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = TitleFieldModel::class;

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
        'title',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Titles field');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws \Exception
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Translatable::make(__('Title'), 'title')
                ->singleLine(),
        ];
    }
}
