<?php

namespace Webid\Cms\App\Nova\Components;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use IDF\HtmlCard\HtmlCard;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Webid\Cms\App\Models\Components\CodeSnippetComponent as CodeSnippetComponentModel;
use Webid\Cms\Modules\JavaScript\Nova\CodeSnippet;

class CodeSnippetComponent extends Resource
{
    /** @var CodeSnippetComponentModel $resource */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CodeSnippetComponentModel::class;

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
        return __('Code snippets');
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return __('Code snippet');
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

            BelongsTo::make(__('Code snippet'), 'codeSnippet', CodeSnippet::class)
                ->showCreateRelationButton()
                ->hideFromIndex(),

            Select::make(__('Status'), 'status')
                ->options(CodeSnippetComponentModel::statusLabels())
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->resource->isPublished();
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
}
