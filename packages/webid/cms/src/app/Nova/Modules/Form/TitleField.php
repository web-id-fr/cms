<?php

namespace Webid\Cms\Src\App\Nova\Modules\Form;

use Webid\Cms\Src\App\Facades\LanguageFacade;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Src\App\Models\Modules\Form\TitleField as TitleFieldModel;

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

            Translatable::make('Title')
                ->singleLine()
                ->locales($languages),
        ];
    }
}
