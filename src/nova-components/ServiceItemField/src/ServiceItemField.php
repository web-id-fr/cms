<?php

namespace Webid\ServiceItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Modules\Form\Models\Form;
use Webid\Cms\Modules\Form\Repositories\ServiceRepository;

class ServiceItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ServiceItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $serviceRepository = app()->make(ServiceRepository::class);

        $allService = $serviceRepository->all();
        $allService->map(function ($service) {
            return $service;
        });

        $this->withMeta(['items' => $allService]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $serviceItems = $request[$requestAttribute];
        $serviceItems = collect(json_decode($serviceItems, true));

        $serviceItemIds = [];

        $serviceItems->each(function ($serviceItem, $key) use (&$serviceItemIds) {
            $serviceItemIds[$serviceItem['id']] = ['order' => $key + 1];
        });

        Form::saved(function ($model) use ($serviceItemIds) {
            $model->services()->sync($serviceItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param string|null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->services();

        $valueInArray = [];
        $resource->services->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
