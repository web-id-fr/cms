<?php

namespace Webid\Cms\Modules\Articles\Nova;

use Carbon\Carbon;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Resource;
use Webid\ArticleCategoriesItemField\ArticleCategoriesItemField;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\App\Rules\TranslatableMax;
use Webid\Cms\App\Rules\TranslatableSlug;
use Webid\Cms\Modules\Articles\Models\Article as ArticleModel;
use Webid\TranslatableTool\Translatable;

class Article extends Resource
{
    use TabsOnEdit, HasIconSvg;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ArticleModel::class;

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
     * @return string
     */
    public static function label()
    {
        return __('Articles');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            (new Tabs(__(':resource Details: :title', [
                'resource' => self::singularLabel(),
                'title' => $this->title(),
            ]), [
                __('Parameters') => $this->parameterFields(),
                __('Content') => $this->contentFields(),
                __('SEO') => $this->seoFields(),
            ]))->withToolbar(),
        ];
    }

    /**
     * @return array
     */
    protected function parameterFields(): array
    {
        return [
            ID::make()->sortable(),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required')
                ->sortable(),

            Translatable::make(__('Slug'), 'slug')
                ->help(__('Please use only this type of slug "name-of-the-template"'))
                ->singleLine()
                ->rules('array', new TranslatableMax(100), new TranslatableSlug()),

            ArticleCategoriesItemField::make(__('Categories'), 'categories'),

            Select::make(__('Status'), 'status')
                ->options(ArticleModel::statusLabels())
                ->displayUsingLabels()
                ->rules('integer', 'required')
                ->hideFromIndex(),

            DateTime::make(__('Publish at'), 'publish_at')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return array
     */
    protected function contentFields(): array
    {
        return [
            FilemanagerField::make(__('Article image'), 'article_image')
                ->rules('required')
                ->hideFromIndex()
                ->displayAsImage(),

            Translatable::make(__('Content'), 'content')
                ->trix()
                ->rules('array')
                ->hideFromIndex(),

            Translatable::make(__('Excerpt'), 'extrait')
                ->trix()
                ->rules('array')
                ->hideFromIndex(),
        ];
    }

    /**
     * @return array
     */
    protected function seoFields(): array
    {
        return [
            Heading::make('Meta'),

            Translatable::make(__('Title'), 'metatitle')
                ->singleLine()
                ->hideFromIndex(),

            Translatable::make(__('Description'), 'metadescription')
                ->rules('array')
                ->hideFromIndex(),

            Heading::make('Open graph'),

            Translatable::make(__('Title'), 'opengraph_title')
                ->singleLine()
                ->hideFromIndex(),

            Translatable::make(__('Description'), 'opengraph_description')
                ->rules('array')
                ->hideFromIndex(),

            FilemanagerField::make(__('Picture'), 'opengraph_picture')
                ->hideFromIndex()
                ->displayAsImage(),

            Heading::make(__('Indexation')),

            Boolean::make(__('Index the page'), 'indexation')
                ->withMeta([
                    'value' => data_get($this, 'indexation', true),
                ])->hideFromIndex(),

            Boolean::make(__('Follow the page'), 'follow')
                ->withMeta([
                    'value' => data_get($this, 'follow', true),
                ])->hideFromIndex(),
        ];
    }

    /**
     * @return string
     */
    public static function icon(): string
    {
        return self::svgIcon('articles', package_module_path('Articles/Resources/svg'));
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == ArticleModel::_STATUS_PUBLISHED
            && ($this->publish_at <= Carbon::now() || $this->publish_at == null);
    }
}
