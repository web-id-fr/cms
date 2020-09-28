<?php

namespace Webid\ServiceItemField;

use Webid\Cms\Src\App\Models\Modules\Form\Form;
use Webid\Cms\Src\App\Repositories\Modules\Form\ServiceRepository;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ServiceItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ServiceItemField';

    /** @var $relationModel */
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
        $serviceRepository = app()->make(ServiceRepository::class);

        $allService = $serviceRepository->all();
        $allService->map(function ($service) {
            return $service;
        });

        $this->withMeta(['items' => $allService]);

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
        $serviceItems = json_decode($request[$requestAttribute]);
        $serviceItems = collect(json_decode(json_encode($serviceItems), true));

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
     * @param null $attribute
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
