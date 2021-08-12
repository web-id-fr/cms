<?php

namespace Webid\Cms\Modules\JavaScript\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\Modules\JavaScript\Models\CodeSnippet as CodeSnippetModel;
use Laravel\Nova\Fields\Text;

class CodeSnippet extends Resource
{
    use HasIconSvg;

    /** @var CodeSnippetModel */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = CodeSnippetModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->rules('required'),

            Code::make(__('Code Snippet'), 'source_code')
                ->language('javascript')
                ->hideFromIndex()
        ];
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('code_snippet', package_module_path('JavaScript/Resources/svg'));
    }
}
