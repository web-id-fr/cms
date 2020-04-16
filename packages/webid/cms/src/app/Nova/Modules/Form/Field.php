<?php

namespace Webid\Cms\Src\App\Nova\Modules\Form;

use Webid\Cms\Src\App\Facades\LanguageFacade;
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
        $languages = LanguageFacade::getUsedLanguage();

        return [
            ID::make()->sortable(),

            Select::make('Field type')
                ->options(config('fields_type'))
                ->hideFromIndex(),

            Text::make('Field name'),

            ConditionalContainer::make([
                Flexible::make('Field items')
                    ->addLayout('Item section', 'option', [
                        Translatable::make('Item')
                            ->singleLine()
                            ->locales($languages),
                    ])->button('Add item')
            ])->if('field_type = ' . array_search('select', config('fields_type'))),

            ConditionalContainer::make([
                Translatable::make('Placeholder')
                    ->singleLine()
                    ->locales($languages),
            ])->if('field_type != ' . array_search('select', config('fields_type')) . ' AND field_type != ' . array_search('file', config('fields_type'))),

            Boolean::make('Required')
                ->withMeta([
                    'value' => data_get($this, 'required', false),
                ]),
        ];
    }
}
