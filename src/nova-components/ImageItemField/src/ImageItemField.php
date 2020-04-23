<?php

namespace Webid\ImageItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Src\App\Models\Modules\Slideshow\Slideshow;
use Webid\Cms\Src\App\Repositories\Modules\Slideshow\SlideRepository;

class ImageItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ImageItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        $slideRepository = app()->make(SlideRepository::class);

        $allSlide = $slideRepository->all();
        $allSlide->map(function ($slide) {
            $slide->imageAsset = asset($slide->image);
            return $slide;
        });

        $this->withMeta(['items' => $allSlide]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return mixed|void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $slideItems = json_decode($request[$requestAttribute]);
        $slideItems = collect(json_decode(json_encode($slideItems), true));

        $slideItemIds = [];

        $slideItems->each(function ($serviceItem, $key) use (&$slideItemIds) {
            $slideItemIds[$serviceItem['id']] = ['order' => $key + 1];
        });

        Slideshow::saved(function ($model) use ($slideItemIds) {
            $model->slides()->sync($slideItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->chargeSlideItems();

        $valueInArray = [];
        $resource->slide_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
