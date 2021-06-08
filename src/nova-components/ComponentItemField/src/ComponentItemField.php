<?php

namespace Webid\ComponentItemField;

use App\Models\Template;
use App\Services\ComponentsService;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;

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
     * @param callable|null $resolveCallback
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $componentsService = app(ComponentsService::class);

        $this->withMeta([
            'items' => $componentsService->getAllComponents(),
        ]);

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
            /** @var Template $model */
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
