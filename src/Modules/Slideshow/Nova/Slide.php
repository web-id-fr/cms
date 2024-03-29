<?php

namespace Webid\Cms\Modules\Slideshow\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Webid\AdvancedUrlField\AdvancedUrl;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Modules\Slideshow\Models\Slide as SlideModel;
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
     * @return array|string|null
     */
    public static function label()
    {
        return __('Slides');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required'),

            Translatable::make(__('Description'), 'description')
                ->trix()
                ->asHtml()
                ->hideFromIndex(),

            Translatable::make(__('CTA name'), 'cta_name')
                ->singleLine()
                ->rules('array')
                ->hideFromIndex(),

            AdvancedUrl::make(__('CTA link'), 'cta_url')
                ->rules('array')
                ->hideFromIndex(),

            AdvancedUrl::make(__('Url'), 'url')
                ->rules('array')
                ->hideFromIndex(),

            FilemanagerField::make(__('Image'), 'image')
                ->displayAsImage(),

            Translatable::make(__('Image balise alt'), 'image_alt')
                ->singleLine()
                ->hideFromIndex(),
        ];
    }
}
