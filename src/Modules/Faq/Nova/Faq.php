<?php

namespace Webid\Cms\Modules\Faq\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Resource;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\Modules\Faq\Models\Faq as FaqModel;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Webid\TranslatableTool\Translatable;

class Faq extends Resource
{
    use HasIconSvg;

    /** @var FaqModel */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FaqModel::class;

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

            Translatable::make(__('Question'), 'question')
                ->trix()
                ->hideFromIndex()
                ->asHtml(),

            Translatable::make(__('Answer'), 'answer')
                ->trix()
                ->hideFromIndex()
                ->asHtml(),

            Number::make(__('Order'), 'order')
                ->min(0)
                ->step(1),

            BelongsTo::make(__('Theme'), 'FaqTheme', FaqTheme::class)
                ->showCreateRelationButton(),

            Select::make(__('Status'), 'status')
                ->options(FaqModel::statusLabels())
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->sortable(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('faq', package_module_path('Faq/Resources/svg'));
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->resource->status == FaqModel::_STATUS_PUBLISHED;
    }
}
