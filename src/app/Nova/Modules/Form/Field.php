<?php

namespace Webid\Cms\Src\App\Nova\Modules\Form;

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
use Webid\Cms\Src\App\Models\Modules\Form\Field as FieldModel;

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

            Select::make('Field type')
                ->options(config('fields_type'))
                ->hideFromIndex(),

            Text::make('Field name'),

            ConditionalContainer::make([
                Flexible::make('Field items', 'field_options')
                    ->addLayout('Item section', 'option', [
                        Translatable::make('Item')
                            ->singleLine(),
                    ])->button('Add item')
            ])->if('field_type = ' . array_search('select', config('fields_type'))),

            ConditionalContainer::make([
                Translatable::make('Date field title')
                    ->singleLine(),

                Translatable::make('Date field placeholder')
                    ->singleLine(),

                Text::make('Field name time'),

                Translatable::make('Time field title')
                    ->singleLine(),

                Translatable::make('Time field placeholder')
                    ->singleLine(),

                Text::make('Field name duration'),

                Translatable::make('Duration field title')
                    ->singleLine(),

                Flexible::make('Duration items', 'field_options')
                    ->addLayout('Item section', 'option', [
                        Translatable::make('Item')
                            ->singleLine(),
                    ])->button('Add item')
            ])->if('field_type = ' . array_search('date-time', config('fields_type'))),

            ConditionalContainer::make([
                Translatable::make('Placeholder')
                    ->singleLine(),
            ])->useAndOperator()
                ->if('field_type != ' . array_search('select', config('fields_type')))
                ->if('field_type != ' . array_search('file', config('fields_type')))
                ->if('field_type != ' . array_search('date-time', config('fields_type'))),

            Boolean::make('Required')
                ->withMeta([
                    'value' => data_get($this, 'required', false),
                ]),
        ];
    }
}
