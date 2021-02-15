<?php

namespace Webid\ComponentItemField;

use App\Models\Template;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;
use Webid\Cms\App\Repositories\Components\GalleryComponentRepository;
use Webid\Cms\App\Repositories\Components\NewsletterComponentRepository;

class ComponentItemField extends Field
{
    /** @var GalleryComponentRepository  */
    protected $galleryComponentRepository;

    /** @var NewsletterComponentRepository  */
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
     * @param callable|null $resolveCallback
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $this->galleryComponentRepository = app(GalleryComponentRepository::class);
        $this->newsletterComponentRepository = app(NewsletterComponentRepository::class);
        $components = collect();
        $allGalleriesComponents = collect();
        $allNewsletterComponents = collect();

        $this->loadComponents(
            $this->galleryComponentRepository->getPublishedComponents(),
            GalleryComponent::class,
            $allGalleriesComponents
        );
        $this->loadComponents(
            $this->newsletterComponentRepository->getPublishedComponents(),
            NewsletterComponent::class,
            $allNewsletterComponents
        );

        $components[config('components.' . GalleryComponent::class . '.title')] = $allGalleriesComponents;
        $components[config('components.' . NewsletterComponent::class . '.title')] = $allNewsletterComponents;

        $this->withMeta([
            'items' => $components,
        ]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param Collection $items
     * @param string $model
     *
     * @return Collection
     */
    protected function mapItems(Collection $items, string $model)
    {
        return $items->each(function ($item) use ($model) {
            $item->component_type = $model;
            $item->component_nova = config("components.$model.nova");
            $item->component_image = asset(config("components.$model.image"));
            return $item;
        });
    }

    /**
     * @param Collection $publishComponent
     * @param string $model
     * @param Collection $allComponents
     *
     * @return Collection
     */
    protected function loadComponents(Collection $publishComponent, string $model, Collection $allComponents)
    {
        $allPublishComponents = $this->mapItems($publishComponent, $model);
        $allPublishComponents->each(function ($component) use (&$allComponents) {
            $allComponents->push($component);
        });

        return $allComponents;
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
        $components = $request[$requestAttribute];
        $components = collect(json_decode($components, true));

        $galleryComponentIds = [];
        $newsletterComponentIds = [];

        foreach ($components as $key => $component) {
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
     *
     * @return void
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
