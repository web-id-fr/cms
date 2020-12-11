<?php

namespace Webid\Cms\App\Nova\Modules\Form;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Webid\RecipientItemField\RecipientItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\App\Models\Modules\Form\Service as ServiceModel;

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
     * Get the displayable label of the resource.
     *
     * @return string
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
                ->rules('array'),

            RecipientItemField::make(__('Recipients'), 'recipients')
                ->onlyOnForms(),
        ];
    }
}
