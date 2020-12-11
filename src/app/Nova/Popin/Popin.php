<?php

namespace  Webid\Cms\App\Nova\Popin;

use OptimistDigital\MultiselectField\Multiselect;
use Webid\Cms\App\Models\Popin\Popin as PopinModel;
use \Eminiarts\Tabs\Tabs;
use \Eminiarts\Tabs\TabsOnEdit;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Webid\Cms\App\Nova\Template;
use Webid\TranslatableTool\Translatable;
use App\Nova\Resource;
use Infinety\Filemanager\FilemanagerField;

class Popin extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = PopinModel::class;

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
        'id',
        'title',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Popins');
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
            new Tabs('Tabs', [
                __('Parameters') => $this->parametersTab(),
                __('Content') => $this->contentTab(),
                __('Settings') => $this->settingsTab()
            ])
        ];
    }

    /**
     * Retourne les champs affichés dans l'onglet Parameters
     *
     * @return array
     */
    protected function parametersTab()
    {
        return [
            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->sortable()
                ->rules('nullable', 'max:255'),

            Multiselect::make(__('Templates'), 'templates')
                ->belongsToMany(Template::class),

            Select::make(__('Status'), 'status')
                ->options(PopinModel::STATUSES)
                ->displayUsingLabels()
                ->rules('nullable', 'integer')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),

        ];
    }

    /**
     * Retourne les champs affichés dans l'onglet Content
     *
     * @return array
     */
    protected function contentTab()
    {
        return [
            FilemanagerField::make(__('Image')),

            Translatable::make(__('Description'), 'description')
                ->trix()
                ->asHtml()
                ->hideFromIndex(),

            Boolean::make(__('Display a call-to-action'), 'display_call_to_action')
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                Translatable::make(__('CTA title'), 'button_1_title')
                    ->singleLine()
                    ->hideFromIndex(),

                Translatable::make(__('CTA link'), 'button_1_url')
                    ->singleLine()
                    ->hideFromIndex(),
            ])->dependsOn('display_call_to_action', true),

            Boolean::make(__('Display a second call-to-action'), 'display_second_button')
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                Translatable::make(__('CTA title 2'), 'button_2_title')
                    ->singleLine()
                    ->hideFromIndex(),

                Translatable::make(__('CTA link 2'), 'button_2_url')
                    ->singleLine()
                    ->hideFromIndex(),

            ])->dependsOn('display_second_button', true),
        ];
    }

    /**
     * Retourne les champs affichés dans l'onglet Settings
     *
     * @return array
     */
    protected function settingsTab()
    {
        return [
            Select::make(__('Opening rule'), 'type')
                ->options([
                    'auto' => __('Timer after loading the page'),
                    'focus' => __('Exit popin'),
                ])
                ->displayUsingLabels()
                ->rules('nullable', 'string'),

            NovaDependencyContainer::make([
                Number::make(__('Delay before displaying the popin (in seconds)'), 'delay')
                    ->hideFromIndex(),
            ])->dependsOn('type', 'auto'),

            NovaDependencyContainer::make([
                Text::make(__('Button name'), 'button_name')
                    ->hideFromIndex(),
            ])->dependsOn('type', 'button'),

            Boolean::make(__('Display on mobile'), 'mobile_display')
                ->hideFromIndex(),

            Number::make(__('Max Display'), 'max_display')->min(1)->step(1)->nullable()
        ];
    }

    /**
     * @return string
     */
    public static function icon(): string
    {
        return '<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="none" d="M0 0h24v24H0V0z"/>
                    <path fill="var(--sidebar-icon)"
                          d="M5 14.5h14v-6H5v6zM11 .55V3.5h2V.55h-2zm8.04 2.5l-1.79 1.79 1.41 1.41 
                          1.8-1.79-1.42-1.41zM13 22.45V19.5h-2v2.95h2zm7.45-3.91l-1.8-1.79-1.41 1.41 1.79 1.8 
                          1.42-1.42zM3.55 4.46l1.79 1.79 1.41-1.41-1.79-1.79-1.41 1.41zm1.41 
                          15.49l1.79-1.8-1.41-1.41-1.79 1.79 1.41 1.42z"/>
                </svg>';
    }

    /**
     * Si le status est publié  : VERT

     * Si ce n'est pas le cas ROUGE
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == PopinModel::_STATUS_PUBLISHED;
    }
}
