<?php

namespace Webid\Cms\App\Nova\Modules\Form;

use App\Nova\Resource;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Flexible;
use Webid\Cms\App\Models\Modules\Form\Field as FieldModel;

class Field extends Resource
{
    use HasConditionalContainer;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FieldModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'field_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'field_name',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Fields');
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

            Select::make(__('Field type'), 'field_type')
                ->options(config('fields_type'))
                ->hideFromIndex(),

            Text::make(__('Field name')),

            ConditionalContainer::make([
                Flexible::make(__('Field items'), 'field_options')
                    ->addLayout(__('Item section'), 'option', [
                        Translatable::make(__('Item'))
                            ->singleLine(),
                    ])->button(__('Add option'))
            ])->if('field_type = ' . array_search('select', config('fields_type'))),

            ConditionalContainer::make([
                Translatable::make(__('Date field title'), 'date_field_title')
                    ->singleLine(),

                Translatable::make(__('Date field placeholder'), 'date_field_placeholder')
                    ->singleLine(),

                Text::make(__('Field name time'), 'field_name_time'),

                Translatable::make(__('Time field title'), 'time_field_title')
                    ->singleLine(),

                Translatable::make(__('Time field placeholder'), 'time_field_placeholder')
                    ->singleLine(),

                Text::make(__('Field name duration'), 'field_name_duration'),

                Translatable::make(__('Duration field title'), 'duration_field_title')
                    ->singleLine(),

                Flexible::make(__('Duration items'), 'field_options')
                    ->addLayout(__('Item section'), 'option', [
                        Translatable::make('Item')
                            ->singleLine(),
                    ])->button(__('Add item'))
            ])->if('field_type = ' . array_search('date-time', config('fields_type'))),

            ConditionalContainer::make([
                Translatable::make(__('Placeholder'), 'placeholder')
                    ->singleLine(),
            ])->useAndOperator()
                ->if('field_type != ' . array_search('select', config('fields_type')))
                ->if('field_type != ' . array_search('file', config('fields_type')))
                ->if('field_type != ' . array_search('date-time', config('fields_type'))),

            Boolean::make(__('Required'), 'required')
                ->withMeta([
                    'value' => data_get($this, 'required', false),
                ]),
        ];
    }
}
