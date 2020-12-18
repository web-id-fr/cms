<?php

namespace Webid\Cms\Modules\Newsletter\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Resource;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Webid\Cms\Modules\Newsletter\Models\Newsletter as NewletterModel;

class Newsletter extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = NewletterModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'email'
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
            Text::make(__('Email'), 'email')
                ->sortable()
                ->rules('required', 'unique:newsletters,email,{{resourceId}}', 'email'),

            DateTime::make(__('Created At'), 'created_at')
                ->rules('required'),

            Text::make(__('Language'), 'lang')
                ->rules('required'),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new DownloadExcel,
        ];
    }

    /**
     * @return string
     */
    public static function icon(): string
    {
        return '<svg class="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="var(--sidebar-icon)"
                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 
                        4l-8 5-8-5V6l8 5 8-5v2z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>';
    }
}
