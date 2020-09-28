<?php

namespace Webid\TemplateItemField;

use Webid\Cms\Src\App\Models\Popin\Popin;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

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
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        $templateRepository = app()->make(TemplateRepository::class);

        $allField = $templateRepository->getPublishedTemplates();
        $allField->map(function ($template) {
            return $template;
        });

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

        if(get_class($model) == Popin::class) {
            Popin::saved(function ($model) use ($fieldItemIds) {
                $model->templates()->sync($fieldItemIds);
            });
        }
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
