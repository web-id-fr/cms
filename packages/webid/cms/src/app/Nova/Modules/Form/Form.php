<?php

namespace Webid\Cms\Src\App\Nova\Modules\Form;

use Webid\Cms\Src\App\Facades\LanguageFacade;
use App\Nova\Resource;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Webid\FieldItemField\FieldItemField;
use Webid\RecipientItemField\RecipientItemField;
use Webid\ServiceItemField\ServiceItemField;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Src\App\Models\Modules\Form\Form as FormModel;

class Form extends Resource
{
    use HasDependencies;

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
            ID::make()->sortable(),

            Translatable::make('Title')
                ->singleLine()
                ->locales($languages),

            Translatable::make('Description')
                ->trix()
                ->rules('array')
                ->hideFromIndex()
                ->locales($languages)
                ->asHtml(),

            Select::make('Status', 'status')
                ->options(FormModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('integer', 'required')
                ->hideFromIndex(),

            FieldItemField::make('Fields')
                ->onlyOnForms(),

            Select::make('Recipient type')
                ->options(FormModel::TYPE_TO_SERVICE)
                ->rules('required')
                ->hideFromIndex(),

            NovaDependencyContainer::make([
                RecipientItemField::make('Recipients')
                    ->onlyOnForms(),
            ])->dependsOn('recipient_type', formModel::_RECIPIENTS),

            NovaDependencyContainer::make([
                Translatable::make('Title service')
                    ->singleLine()
                    ->locales($languages),

                ServiceItemField::make('Services')
                    ->onlyOnForms(),
            ])->dependsOn('recipient_type', FormModel::_SERVICES),

            Boolean::make('Published', function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * Si le status est publié  : VERT
     *
     * Si ce n'est pas le cas ROUGE
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == FormModel::_STATUS_PUBLISHED;
    }
}
