<?php

namespace Webid\Cms\App\Nova;

use Carbon\Carbon;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Resource;
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
        return __('Templates');
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

    /**
     * Affiche les champs pour le paramétrage de l'article
     *
     * @return array
     */
    protected function parameterFields()
    {
        return [
            ID::make()->sortable(),

            Boolean::make(__('Homepage'), 'homepage'),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required')
                ->sortable(),

            Translatable::make(__('Menu description'), 'menu_description')
                ->help(__('This field is optional and allows you to add a short description below the title in the sub-menu.'))
                ->singleLine()
                ->sortable(),

            Translatable::make(__('Slug'), 'slug')
                ->help(__('Please use only this type of slug "name-of-the-template"'))
                ->singleLine()
                ->rules('array', new TranslatableMax(100), new TranslatableSlug())
                ->onlyOnForms(),

            PageUrlItemField::make('Url', 'slug')
                ->projectUrl(config('app.url'))
                ->showOnIndex()
                ->showOnDetail()
                ->hideWhenUpdating()
                ->hideWhenCreating(),

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
            Heading::make('Meta'),

            Translatable::make(__('Title'), 'metatitle')
                ->singleLine()
                ->hideFromIndex(),

            Translatable::make(__('Description'), 'metadescription')
                ->trix()
                ->asHtml()
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
        return '<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" 
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path fill="var(--sidebar-icon)"
                        d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9
                        2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>';
    }

    /**
     * Si le status est publié et que la date de début est avant maintenant : VERT
     * OU
     * Si le status est publié et que la date de début est vide : VERT
     *
     * Si ce n'est pas le cas ROUGE
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == TemplateModel::_STATUS_PUBLISHED
            && ($this->publish_at <= Carbon::now() || $this->publish_at == null);
    }
}
