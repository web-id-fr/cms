<?php

namespace Webid\ComponentField;

use App\Models\Template;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Repositories\Components\GalleryComponentRepository;

class ComponentField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ComponentField';

    public $relationModel;


    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        $galleryComponentRepository = app()->make(GalleryComponentRepository::class);

        $allComponent = $galleryComponentRepository->all();
        $allComponent->map(function ($gallery_component) {
            $gallery_component->component_type = GalleryComponent::class;
            $gallery_component->component_nova = config("components.$gallery_component->component_type.nova");
            $gallery_component->component_image = asset(config("components.$gallery_component->component_type.image"));
            return $gallery_component;
        });

        $this->withMeta(['items' => $allComponent]);

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
        $components = json_decode($request[$requestAttribute]);
        $components = collect(json_decode(json_encode($components), true));

        $galleryComponentIds = [];

        $components->each(function (
            $component,
            $key
        ) use (
            &$galleryComponentIds
        ) {
            if ($component['component_type'] == GalleryComponent::class) {
                $galleryComponentIds[$component['id']] = ['order' => $key + 1];
            }
        });

        Template::saved(function ($model) use (
            $galleryComponentIds
        ) {
            $model->galleryComponents()->sync($galleryComponentIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->chargeComponents();

        $valueInArray = [];
        $resource->components_item->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
