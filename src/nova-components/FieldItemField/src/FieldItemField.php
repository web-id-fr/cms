<?php

namespace Webid\FieldItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Modules\Form\Models\Form;
use Webid\Cms\Modules\Form\Models\TitleField;
use Webid\Cms\Modules\Form\Repositories\FieldRepository;
use Webid\Cms\Modules\Form\Repositories\TitleFieldRepository;
use Webid\Cms\Modules\Form\Models\Field as FieldModel;
use function Safe\json_decode;

class FieldItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'FieldItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $fieldRepository = app()->make(FieldRepository::class);
        $titleFieldRepository = app()->make(TitleFieldRepository::class);

        $allField = $fieldRepository->all();
        $allField->map(function ($field) {
            $field->formable_type = FieldModel::class;
            $field->title = $field->field_name;
            return $field;
        });
        $allTitleFields = $titleFieldRepository->all();
        $allTitleFields->map(function ($titleField) {
            $titleField->formable_type = TitleField::class;
            return $titleField;
        });

        $allTitleFields->each(function ($field) use (&$allField) {
            $allField->push($field);
        });

        $this->withMeta(['items' => $allField]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return mixed|void
     *
     * @throws \Safe\Exceptions\JsonException
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $fieldItems = $request[$requestAttribute];
        $fieldItems = collect(json_decode($fieldItems, true));

        $fieldItemIds = [];
        $titleFieldItemIds = [];

        $fieldItems->each(function ($fieldItem, $key) use (&$fieldItemIds, &$titleFieldItemIds) {
            if ($fieldItem['formable_type'] == FieldModel::class) {
                $fieldItemIds[$fieldItem['id']] = ['order' => $key + 1];
            } else {
                $titleFieldItemIds[$fieldItem['id']] = ['order' => $key + 1];
            }
        });

        Form::saved(function ($model) use ($fieldItemIds, $titleFieldItemIds) {
            $model->fields()->sync($fieldItemIds);
            $model->titleFields()->sync($titleFieldItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->chargeFieldItems();

        $valueInArray = [];
        $resource->field_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
