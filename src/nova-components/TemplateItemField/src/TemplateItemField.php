<?php

namespace Webid\TemplateItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Popin\Popin;
use Webid\Cms\App\Repositories\TemplateRepository;

class TemplateItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'TemplateItemField';

    /**
     * @var $relationModel
     */
    public $relationModel;

    /**
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $templateRepository = app()->make(TemplateRepository::class);

        $allField = $templateRepository->getPublishedTemplates();

        $this->withMeta(['items' => $allField]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param $requestAttribute
     * @param $model
     * @param $attribute
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $fieldItems = json_decode($request[$requestAttribute]);
        $fieldItems = collect(json_decode(json_encode($fieldItems), true));

        $fieldItemIds = [];

        $fieldItems->each(function ($fieldItem, $key) use (&$fieldItemIds) {
            $fieldItemIds[] = $fieldItem['id'];
        });

        app(get_class($model))::saved(function ($model) use ($fieldItemIds) {
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
