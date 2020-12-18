<?php

namespace Webid\Cms\App\Nova\Components;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Webid\Cms\App\Models\Components\NewsletterComponent as NewsletterComponentModel;
use Webid\TranslatableTool\Translatable;

class NewsletterComponent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = NewsletterComponentModel::class;

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Newsletters');
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

            Text::make(__('Name'), 'name')
                ->rules('required'),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->sortable(),

            Translatable::make(__('Placeholder'), 'placeholder')
                ->singleLine()
                ->sortable(),

            Translatable::make(__('CTA Name'), 'cta_name')
                ->singleLine()
                ->sortable(),

            Select::make(__('Status'), 'status')
                ->options(NewsletterComponentModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('integer')
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
        return $this->status == NewsletterComponentModel::_STATUS_PUBLISHED;
    }
}
