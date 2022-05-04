<?php

namespace Webid\Cms\App\Nova;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Webid\Cms\App\Repositories\TemplateRepository;
use Webid\Cms\App\Rules\TranslatableMax;
use Webid\Cms\App\Rules\TranslatableSlug;
use \Eminiarts\Tabs\Tabs;
use \Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Webid\ComponentItemField\ComponentItemField;
use Webid\PageUrlItemField\PageUrlItemField;
use Webid\PreviewItemField\PreviewItemField;
use Webid\TranslatableTool\Translatable;
use App\Models\Template as TemplateModel;

class Template extends Resource
{
    use TabsOnEdit;

    /** @var TemplateModel $resource */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = TemplateModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    public function title()
    {
        /** @var string $title */
        $title = $this->resource->title;
        /** @var string $slug */
        $slug = $this->resource->slug;

        return "$title - $slug";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
        'slug',
    ];

    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Templates');
    }

    /**
     * @param Request $request
     *
     * @return array
     *
     * @throws \Exception
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
            PreviewItemField::make('preview_btn')->onlyOnForms()
        ];
    }

    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        return array_merge(
            parent::serializeForIndex($request, $fields),
            [
                'urls' => $this->getFullUrls(),
                'titles' => $this->resource->getTranslations('title'),
            ]
        );
    }

    public function getFullUrls(): array
    {
        $urls = [];
        $translatedSlugs = $this->resource->getTranslations('slug');

        foreach ($translatedSlugs as $locale => $slug) {
            $urls[$locale] = URL::to($this->resource->getFullPath($locale));
        }

        return $urls;
    }

    public function getParentPageId(): int
    {
        if (!empty($this->resource->parent_page_id)) {
            return $this->resource->parent_page_id;
        }

        $templateRepository = app(TemplateRepository::class);
        $homepageId = $templateRepository->getIdForHomepage();

        if (!empty($homepageId) && !$this->resource->homepage) {
            return $homepageId->getKey();
        }

        return 0;
    }

    public static function icon(): string
    {
        return '<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" 
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path fill="currentColor"
                        d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9
                        2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>';
    }

    public function isPublished(): bool
    {
        return $this->resource->status == TemplateModel::_STATUS_PUBLISHED
            && ($this->resource->publish_at <= Carbon::now() || $this->resource->publish_at == null);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function parameterFields()
    {
        return [
            ID::make()->sortable(),

            Boolean::make(__('Homepage'), 'homepage'),

            Boolean::make(__('Contains articles list'), 'contains_articles_list'),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required')
                ->sortable(),

            Translatable::make(__('Menu description'), 'menu_description')
                ->help(__(
                    'This field is optional and allows you to add a short description below the title in the sub-menu.'
                ))
                ->singleLine()
                ->hideFromIndex()
                ->sortable(),

            Translatable::make(__('Slug'), 'slug')
                ->help(__('Please use only this type of slug "name-of-the-template"'))
                ->singleLine()
                ->rules('array', new TranslatableMax(100), new TranslatableSlug())
                ->onlyOnForms(),

            PageUrlItemField::make('Url', 'slug')
                ->projectUrl(config('app.url'))
                ->urls($this->getFullUrls())
                ->showOnIndex()
                ->showOnDetail()
                ->hideWhenUpdating()
                ->hideWhenCreating(),

            BelongsTo::make(__('Parent page'), 'parent', Template::class)
                ->withMeta([
                    'belongsToId' => $this->getParentPageId()
                ])->nullable()
                ->searchable(),

            Select::make(__('Status'), 'status')
                ->options(TemplateModel::statusLabels())
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
     * Affiche les champs pour le contenu de la page
     *
     * @return array
     */
    protected function contentFields()
    {
        return [
            ComponentItemField::make(__('Components'), 'components')
                ->hideFromIndex(),
        ];
    }

    /**
     * Affiche les champs pour les balises meta de la page
     *
     * @return array
     */
    protected function seoFields()
    {
        return [
            BelongsTo::make(__('Reference page'), 'referencePage', Template::class)
                ->nullable()
                ->searchable(),

            Heading::make('Meta'),

            Translatable::make(__('Title'), 'metatitle')
                ->singleLine()
                ->hideFromIndex(),

            Translatable::make(__('Description'), 'metadescription')
                ->trix()
                ->asHtml()
                ->rules('array')
                ->hideFromIndex(),

            Translatable::make(__('Keywords'), 'meta_keywords')
                ->rules('array')
                ->hideFromIndex(),

            Heading::make('Open graph'),

            Translatable::make(__('Title'), 'opengraph_title')
                ->singleLine()
                ->hideFromIndex(),

            Translatable::make(__('Description'), 'opengraph_description')
                ->trix()
                ->asHtml()
                ->rules('array')
                ->hideFromIndex(),

            FilemanagerField::make(__('Image'), 'opengraph_picture')
                ->hideFromIndex()
                ->displayAsImage(),

            Translatable::make(__('Image balise alt'), 'opengraph_picture_alt')
                ->singleLine()
                ->hideFromIndex(),

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
}
