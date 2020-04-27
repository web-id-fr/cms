<?php

namespace Webid\ComponentItemField;

use App\Models\Template;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Models\Components\NewsletterComponent;
use Webid\Cms\Src\App\Repositories\Components\GalleryComponentRepository;
use Webid\Cms\Src\App\Repositories\Components\NewsletterComponentRepository;

class ComponentItemField extends Field
{
    protected $galleryComponentRepository;
    protected $newsletterComponentRepository;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'ComponentItemField';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        $this->galleryComponentRepository = app(GalleryComponentRepository::class);
        $this->newsletterComponentRepository = app(NewsletterComponentRepository::class);
        $allComponents = collect();

        $this->loadComponents(
            $this->galleryComponentRepository->getPublishedComponents(),
            GalleryComponent::class,
            $allComponents
        );
        $this->loadComponents(
            $this->newsletterComponentRepository->getPublishedComponents(),
            NewsletterComponent::class,
            $allComponents
        );

        $this->withMeta(['items' => $allComponents]);
        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param $items
     * @param $model
     *
     * @return mixed
     */
    protected function mapItems($items, $model)
    {
        return $items->each(function ($item) use ($model) {
            $item->component_type = $model;
            $item->component_nova = config("components.$model.nova");
            $item->component_image = asset(config("components.$model.image"));
            return $item;
        });
    }

    /**
     * @param $publishComponent
     * @param $model
     * @param $allComponents
     *
     * @return mixed
     */
    protected function loadComponents($publishComponent, $model, $allComponents)
    {
        $allPublishComponents = $this->mapItems($publishComponent, $model);
        $allPublishComponents->each(function ($component) use (&$allComponents) {
            $allComponents->push($component);
        });

        return $allComponents;
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
        $newsletterComponentIds = [];

        foreach ($components as $component => $key) {
            switch ($component['component_type']) {
                case GalleryComponent::class:
                    $galleryComponentIds[$component['id']] = ['order' => $key + 1];
                    break;
                case NewsletterComponent::class:
                    $newsletterComponentIds[$component['id']] = ['order' => $key + 1];
                    break;
            }
        }

        Template::saved(function ($model) use (
            $galleryComponentIds,
            $newsletterComponentIds
        ) {
            $model->galleryComponents()->sync($galleryComponentIds);
            $model->newsletterComponents()->sync($newsletterComponentIds);
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
        $resource->component_items->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }
}
