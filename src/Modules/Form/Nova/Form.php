<?php

namespace Webid\Cms\Modules\Form\Nova;

use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\ConfirmationEmailItemField\ConfirmationEmailItemField;
use Webid\FieldItemField\FieldItemField;
use Webid\RecipientItemField\RecipientItemField;
use Webid\ServiceItemField\ServiceItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Modules\Form\Models\Form as FormModel;

class Form extends Resource
{
    use HasDependencies, HasIconSvg;

    /** @var FormModel $resource */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FormModel::class;

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
        'name',
    ];

    /**
     * @return array|string|null
     */
    public static function label()
    {
        return __('Forms');
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
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->rules('required'),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required'),

            Translatable::make(__('Description'), 'description')
                ->trix()
                ->rules('array')
                ->hideFromIndex()
                ->asHtml(),

            FieldItemField::make(__('Fields'), 'fields')
                ->hideFromIndex(),

            ConfirmationEmailItemField::make(__('Confirmation email field'), 'confirmation_email_name')
                ->canSee(function ($request) {
                    return config('form.send_email_confirmation');
                }),

            Translatable::make(__('CTA name'), 'cta_name')
                ->singleLine()
                ->rules('array', 'required')
                ->hideFromIndex(),

            Translatable::make(__('RGPD mention'), 'rgpd_mention')
                ->trix()
                ->rules('array')
                ->hideFromIndex()
                ->asHtml(),

            Select::make(__('Recipient type'), 'recipient_type')
                ->options(FormModel::TYPE_TO_SERVICE)
                ->rules('required')
                ->onlyOnForms(),

            NovaDependencyContainer::make([
                RecipientItemField::make(__('Recipients'), 'recipients')
                    ->hideFromIndex(),
            ])->dependsOn('recipient_type', formModel::_RECIPIENTS),

            NovaDependencyContainer::make([
                Translatable::make(__('Title service'), 'title_service')
                    ->singleLine(),

                ServiceItemField::make(__('Services'), 'services')
                    ->hideFromIndex(),
            ])->dependsOn('recipient_type', FormModel::_SERVICES),

            Select::make(__('Status'), 'status')
                ->options(FormModel::statusLabels())
                ->displayUsingLabels()
                ->rules('integer', 'required')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->resource->status == FormModel::_STATUS_PUBLISHED;
    }

    /**
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('form', package_module_path('Form/Resources/svg'));
    }
}
