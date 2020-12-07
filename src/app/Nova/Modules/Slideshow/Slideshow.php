<?php

namespace Webid\Cms\Src\App\Nova\Slideshow;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Webid\ImageItemField\ImageItemField;
use Webid\SlideItemField\SlideItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Src\App\Models\Modules\Slideshow\Slideshow as SlideshowModel;
use Laravel\Nova\Resource;

class Slideshow extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = SlideshowModel::class;

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
        return __('Slideshows');
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

            Boolean::make(__('Arrows display'), 'js_controls'),

            Boolean::make(__('Automatic slider'), 'js_animate_auto'),

            Number::make(__('Sliding speed'), 'js_speed')
                ->min(1)
                ->step(1)
                ->help(__('By default 5 seconds'))
                ->resolveUsing(function ($js_speed) {
                    if(empty($js_speed)) {
                        return '';
                    }
                    return $js_speed / 1000;
                })->displayUsing(function ($js_speed) {
                    return $js_speed / 1000;
                }),

            ImageItemField::make(__('Slides'), 'slides')
                ->onlyOnForms(),
        ];
    }

    /**
     * @return string
     */
    public static function icon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="var(--sidebar-icon)" d="M7 19h10V4H7v15zm-5-2h4V6H2v11zM18 6v11h4V6h-4z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>';
    }
}
