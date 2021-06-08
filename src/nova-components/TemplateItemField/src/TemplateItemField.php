<?php

namespace Webid\TemplateItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Repositories\TemplateRepository;
use function Safe\json_decode;

class TemplateItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'TemplateItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $templateRepository = app()->make(TemplateRepository::class);

        $allField = $templateRepository->getPublishedTemplates();

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

        $fieldItems->each(function ($fieldItem, $key) use (&$fieldItemIds) {
            $fieldItemIds[] = $fieldItem['id'];
        });

        $class = get_class($model);

        $class::saved(function ($model) use ($fieldItemIds) {
            $model->templates()->sync($fieldItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->templates();

        $valueInArray = [];
        $resource->templates->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
