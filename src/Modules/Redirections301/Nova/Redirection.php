<?php

namespace Webid\Cms\Modules\Redirections301\Nova;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\App\Rules\IsUrlPath;
use Webid\Cms\Modules\Redirections301\Models\Redirection as RedirectionModel;

class Redirection extends Resource
{
    use HasIconSvg;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = RedirectionModel::class;

    /**
     * @param Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make(__('Source'), 'source_url')
                ->placeholder(__('/my-source-url'))
                ->help(__("Accepts only paths, no complete URL. Must start with a / ."))
                ->rules([
                    'required',
                    Rule::unique($this->model()->getTable(), 'source_url')->ignore($this->model()->getKey()),
                    new IsUrlPath,
                ]),

            Text::make(__('Destination'), 'destination_url')
                ->placeholder(__('/my-destination-url'))
                ->help(__("Accepts only paths, no complete URL. Must start with a / ."))
                ->rules([
                    'required',
                    new IsUrlPath,
                ]),
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('redirection', package_module_path('Redirections301/Resources/svg'));
    }
}
