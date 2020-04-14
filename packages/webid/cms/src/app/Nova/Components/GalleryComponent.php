<?php

namespace Webid\Cms\Src\App\Nova\Components;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Fields\Text;
use Webid\Cms\Src\App\Models\Modules\Galleries\Gallery;
use Webid\FieldItemField\GalleryItemField;

class GalleryComponent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Gallery::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * @return string
     */
    public static function label()
    {
        return 'Galleries Components';
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return 'Gallery Component';
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

            Text::make('Name')
                ->rules('required'),

            GalleryItemField::make('galleries')
                ->onlyOnForms(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new HtmlCard())->width('1/3')
                ->view('cards.component', ['model' => self::$model])
                ->center(true),
        ];
    }
}
