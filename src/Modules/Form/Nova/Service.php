<?php

namespace Webid\Cms\Modules\Form\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\RecipientItemField\RecipientItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Modules\Form\Models\Service as ServiceModel;

class Service extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ServiceModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Services');
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

            Translatable::make(__('Name'), "name")
                ->singleLine()
                ->rules('array', 'required'),

            RecipientItemField::make(__('Recipients'), 'recipients')
                ->onlyOnForms(),
        ];
    }
}
