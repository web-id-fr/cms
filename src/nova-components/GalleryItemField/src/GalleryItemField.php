<?php

namespace Webid\GalleryItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\Modules\Galleries\Repositories\GalleryRepository;

class GalleryItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'GalleryItemField';

    /** @var $relationModel */
    public $relationModel;

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $galleryRepository = app()->make(GalleryRepository::class);

        $allGalleries = $galleryRepository->getPublishedGalleries();
        $allGalleries->map(function ($gallery) {
            return $gallery;
        });

        $this->withMeta(['items' => $allGalleries]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param $requestAttribute
     * @param $model
     * @param $attribute
     *
     * @return void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $galleryItems = json_decode($request[$requestAttribute]);
        $galleryItems = collect(json_decode(json_encode($galleryItems), true));

        $GalleryItemIds = [];

        $galleryItems->each(function ($fieldItem, $key) use (&$GalleryItemIds) {
            $GalleryItemIds[$fieldItem['id']] = ['order' => $key + 1];
        });

        GalleryComponent::saved(function ($model) use ($GalleryItemIds) {
            $model->galleries()->sync($GalleryItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->chargeGalleryItems();

        $valueInArray = [];
        $resource->gallery_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
