<?php

namespace Webid\Cms\Src\App\Nova\Components;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use Webid\Cms\Src\App\Models\Components\NewsletterComponent as NewsletterComponentModel;
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
     * @return string
     */
    public static function label()
    {
        return 'Newsletters Components';
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return 'Newsletter Component';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $languages = LanguageFacade::getUsedLanguage();

        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->rules('required'),

            Translatable::make('Title', 'title')
                ->singleLine()
                ->rules('required')
                ->sortable()
                ->locales($languages),

            Translatable::make('CTA Name')
                ->singleLine()
                ->rules('required')
                ->sortable()
                ->locales($languages),

            Select::make('Status', 'status')
                ->options(NewsletterComponentModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->hideFromIndex(),

            Boolean::make('Published', function () {
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
