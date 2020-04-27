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
        $galleryComponentRepository = app()->make(GalleryComponentRepository::class);
        $newsletterComponentRepository = app()->make(NewsletterComponentRepository::class);
        $allComponent = collect();

        // GALLERIES
        $allGalleryComponents = $galleryComponentRepository->getPublishedComponents();
        $allGalleryComponents = $this->mapItems($allGalleryComponents, GalleryComponent::class);
        $allGalleryComponents->each(function ($gallery_component) use (&$allComponent) {
            $allComponent->push($gallery_component);
        });

        // NEWSLETTERS
        $allNewsletterComponents = $newsletterComponentRepository->getPublishedComponents();
        $allNewsletterComponents = $this->mapItems($allNewsletterComponents,NewsletterComponent::class);
        $allNewsletterComponents->each(function ($newsletter_component) use (&$allComponent) {
            $allComponent->push($newsletter_component);
        });

        $this->withMeta(['items' => $allComponent]);
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

        $components->each(function (
            $component,
            $key
        ) use (
            &$galleryComponentIds,
            &$newsletterComponentIds
        ) {
            if ($component['component_type'] == GalleryComponent::class) {
                $galleryComponentIds[$component['id']] = ['order' => $key + 1];
            } elseif ($component['component_type'] == NewsletterComponent::class) {
                $newsletterComponentIds[$component['id']] = ['order' => $key + 1];
            }
        });

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
