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
use Webid\Cms\Src\App\Nova\Components\GalleryComponent;
use Webid\Cms\Src\App\Nova\Components\NewsletterComponent;
use Webid\Cms\Src\App\Nova\Modules\Galleries\Gallery;
use Webid\Cms\Src\App\Nova\Newsletter\Newsletter;
use Webid\Cms\Src\App\Nova\Popin\Popin;
use Webid\Cms\Src\App\Nova\Template;
use Webid\ComponentTool\ComponentTool;
use Webid\LanguageTool\LanguageTool;
use Joedixon\NovaTranslation\NovaTranslation;

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
            return in_array($user->email, [
                //
            ]);
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
            //
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
            new CollapsibleResourceManager([
                'navigation' => [
                    TopLevelResource::make([
                        'label' => 'Templates',
                        'badge' => null,
                        'linkTo' => Template::class,
                    ]),
                    TopLevelResource::make([
                        'resources' => [
                            Group::make([
                                'label' => 'Components',
                                'expanded' => false,
                                'resources' => [
                                    InternalLink::make([
                                        'label' => 'List of Components',
                                        'badge' => null,
                                        'icon' => null,
                                        'target' => '_self',
                                        'path' => '/component-tool',
                                    ]),
                                    GalleryComponent::class,
                                    NewsletterComponent::class
                                ]
                            ]),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Modules',
                        'resources' => [
                            Group::make([
                                'label' => 'Modules',
                                'expanded' => false,
                                'resources' => [
                                    Gallery::class
                                ]
                            ]),
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => 'Newsletter',
                        'badge' => null,
                        'linkTo' => Newsletter::class,
                    ]),
                    TopLevelResource::make([
                        'label' => 'Popins',
                        'badge' => null,
                        'linkTo' => Popin::class,
                    ]),
                    TopLevelResource::make([
                        'label' => 'Users',
                        'badge' => null,
                        'linkTo' => User::class,
                    ]),
                ]
            ]),
            new NovaTranslation,
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
