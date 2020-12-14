<?php

namespace App\Providers;

use App\Nova\User;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\Group;
use DigitalCreative\CollapsibleResourceManager\Resources\InternalLink;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Webid\CardActions\CardActions;
use Webid\Cms\App\Nova\Menu\Menu;
use Webid\Cms\App\Nova\Menu\MenuCustomItem;
use Webid\Cms\App\Nova\Modules\Form\Field;
use Webid\Cms\App\Nova\Modules\Form\Form;
use Webid\Cms\App\Nova\Modules\Form\Recipient;
use Webid\Cms\App\Nova\Modules\Form\Service;
use Webid\Cms\App\Nova\Modules\Form\TitleField;
use Webid\Cms\App\Nova\Modules\Galleries\Gallery;
use Webid\Cms\App\Nova\Newsletter\Newsletter;
use Webid\Cms\App\Nova\Popin\Popin;
use Webid\Cms\App\Nova\Slideshow\Slide;
use Webid\Cms\App\Nova\Slideshow\Slideshow;
use Webid\Cms\App\Nova\Template;
use Webid\ComponentTool\ComponentTool;
use Webid\LanguageTool\LanguageTool;
use Webid\MenuTool\MenuTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new CardActions()
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \Infinety\Filemanager\FilemanagerTool(),
            new LanguageTool(),
            new ComponentTool(),
            new MenuTool(),
            new CollapsibleResourceManager([
                'navigation' => [
                    TopLevelResource::make([
                        'label' => __('Menu'),
                        'badge' => null,
                        'linkTo' => Menu::class,
                        'resources' => [
                            Group::make([
                                'label' => __('Menu'),
                                'expanded' => false,
                                'resources' => [
                                    MenuCustomItem::class,
                                    InternalLink::make([
                                        'label' => __('Configuration'),
                                        'badge' => null,
                                        'icon' => null,
                                        'target' => '_self',
                                        'path' => '/menu-tool',
                                    ])
                                ]
                            ]),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Templates'),
                        'badge' => null,
                        'linkTo' => Template::class,
                    ]),
                    TopLevelResource::make([
                        'resources' => [
                            InternalLink::make([
                                'label' => 'List of Components',
                                'badge' => null,
                                'icon' => null,
                                'target' => '_self',
                                'path' => '/component-tool',
                            ]),
                            Group::make([
                                'label' => __('Modules'),
                                'expanded' => false,
                                'resources' => [
                                    Gallery::class,
                                    Group::make([
                                        'label' => __('Form'),
                                        'expanded' => false,
                                        'resources' => [
                                            Form::class,
                                            Field::class,
                                            TitleField::class,
                                            Service::class,
                                            Recipient::class,
                                        ]
                                    ]),
                                    Group::make([
                                        'label' => __('Slideshow'),
                                        'expanded' => false,
                                        'resources' => [
                                            Slideshow::class,
                                            Slide::class
                                        ]
                                    ]),
                                ]
                            ]),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Newsletter'),
                        'badge' => null,
                        'linkTo' => Newsletter::class,
                    ]),
                    TopLevelResource::make([
                        'label' => __('Popins'),
                        'badge' => null,
                        'linkTo' => Popin::class,
                    ]),
                    TopLevelResource::make([
                        'label' => __('Users'),
                        'badge' => null,
                        'linkTo' => User::class,
                    ]),
                ]
            ]),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
