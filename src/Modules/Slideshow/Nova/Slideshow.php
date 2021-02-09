<?php

namespace Webid\Cms\Modules\Slideshow\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\ImageItemField\ImageItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Modules\Slideshow\Models\Slideshow as SlideshowModel;
use Laravel\Nova\Resource;

class Slideshow extends Resource
{
    use HasIconSvg;

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
                    if (empty($js_speed)) {
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
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('slideshow', package_module_path('Slideshow/Resources/svg'));
    }
}
