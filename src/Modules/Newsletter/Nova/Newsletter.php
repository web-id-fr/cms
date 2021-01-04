<?php

namespace Webid\Cms\Modules\Newsletter\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Resource;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\Modules\Newsletter\Models\Newsletter as NewsletterModel;

class Newsletter extends Resource
{
    use HasIconSvg;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = NewsletterModel::class;

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
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('newsletter', package_module_path('Newsletter/Resources/svg'));
    }
}
