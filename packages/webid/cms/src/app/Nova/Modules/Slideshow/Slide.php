<?php

namespace Webid\Cms\Src\App\Nova\Slideshow;

use Laravel\Nova\Fields\ID;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Src\App\Models\Modules\Slideshow\Slide as SlideModel;
use Laravel\Nova\Resource;

class Slide extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = SlideModel::class;

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
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        $languages = LanguageFacade::getUsedLanguage();

        return [
            ID::make()->sortable(),

            Translatable::make('Title')
                ->singleLine()
                ->rules('required')
                ->locales($languages),

            Translatable::make('Description')
                ->trix()
                ->asHtml()
                ->locales($languages)
                ->hideFromIndex(),

            Translatable::make('CTA name', 'cta_name')
                ->singleLine()
                ->rules('array')
                ->locales($languages)
                ->hideFromIndex(),

            Translatable::make('CTA link', 'cta_url')
                ->singleLine()
                ->rules('array')
                ->locales($languages)
                ->hideFromIndex(),

            Translatable::make('Url')
                ->singleLine()
                ->rules('array')
                ->locales($languages)
                ->hideFromIndex(),

            FilemanagerField::make('Image')
                ->displayAsImage(),
        ];
    }
}
