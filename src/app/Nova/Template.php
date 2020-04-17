<?php

namespace Webid\Cms\Src\App\Nova;

use Carbon\Carbon;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use App\Nova\Resource;
use Webid\Cms\Src\App\Rules\TranslatableMax;
use Webid\Cms\Src\App\Rules\TranslatableSlug;
use \Eminiarts\Tabs\Tabs;
use \Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Webid\ComponentItemField\ComponentItemField;
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $languages = LanguageFacade::getUsedLanguage();

        return [
            new Tabs('Tabs', [
                'Parameters' => $this->parameterFields($languages),
                'Content' => $this->contentFields(),
                'SEO' => $this->seoFields($languages),
            ]),
        ];
    }

    /**
     * Affiche les champs pour le paramétrage de l'article
     *
     * @param $languages
     *
     * @return array
     */
    protected function parameterFields($languages)
    {
        return [
            Boolean::make('Homepage'),

            ID::make()->sortable(),

            Translatable::make('Title', 'title')
                ->singleLine()
                ->rules('required')
                ->sortable()
                ->locales($languages),

            Translatable::make('Slug', 'slug')
                ->help('Please use only this type of slug "name-of-the-template"')
                ->singleLine()
                ->rules('array', new TranslatableMax(100), new TranslatableSlug())
                ->locales($languages),

            Select::make('Status', 'status')
                ->options(TemplateModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('integer', 'required')
                ->hideFromIndex(),

            DateTime::make('Publish at', 'publish_at')
                ->hideFromIndex(),

            Boolean::make('Published', function () {
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
            ComponentItemField::make('Components')
                ->hideFromIndex(),
        ];
    }


    /**
     * Affiche les champs pour les balises meta de la page
     *
     * @param $languages
     *
     * @return array
     */
    protected function seoFields($languages)
    {
        return [
            Translatable::make('Metatitle', 'metatitle')
                ->singleLine()
                ->hideFromIndex()
                ->locales($languages),

            Translatable::make('Metadescription', 'metadescription')
                ->trix()
                ->rules('array')
                ->hideFromIndex()
                ->locales($languages),

            Translatable::make('Opengraph Title', 'opengraph_title')
                ->singleLine()
                ->hideFromIndex()
                ->locales($languages),

            Translatable::make('Opengraph Description', 'opengraph_description')
                ->trix()
                ->rules('array')
                ->hideFromIndex()
                ->locales($languages),

            FilemanagerField::make('Opengraph Picture', 'opengraph_picture')
                ->hideFromIndex()
                ->displayAsImage(),

            Boolean::make('Index the page', 'indexation')
                ->withMeta([
                    'value' => data_get($this, 'indexation', true),
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
