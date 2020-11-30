<?php

namespace Webid\Cms\Src\App\Nova\Components;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Webid\Cms\Src\App\Models\Components\GalleryComponent as GalleryComponentModel;
use Webid\GalleryItemField\GalleryItemField;

class GalleryComponent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = GalleryComponentModel::class;

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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'))
                ->rules('required'),

            GalleryItemField::make(__('galleries'))
                ->onlyOnForms(),

            Select::make(__('Status'), 'status')
                ->options(GalleryComponentModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
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

    /**
     * Si le status est publié  : VERT
     *
     * Si ce n'est pas le cas ROUGE
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == GalleryComponentModel::_STATUS_PUBLISHED;
    }
}
