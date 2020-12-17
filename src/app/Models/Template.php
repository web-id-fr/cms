<?php

namespace App\Models;

use Webid\Cms\App\Models\BaseTemplate;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;

class Template extends BaseTemplate
{
    /** @var $components_item */
    public $component_items;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function galleryComponents()
    {
        return $this->morphedByMany(GalleryComponent::class, 'component')
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function newsletterComponents()
    {
        return $this->morphedByMany(NewsletterComponent::class, 'component')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function chargeComponents()
    {
        $components = collect();
        $gallery_components = $this->galleryComponents;
        $newsletter_components = $this->newsletterComponents;

        $this->mapItems($gallery_components,GalleryComponent::class,$components);
        $this->mapItems($newsletter_components,NewsletterComponent::class,$components);

        $components = $components->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->component_items = $components;
    }

    /**
     * @param $items
     * @param $model
     * @param $components
     *
     * @return mixed
     */
    protected function mapItems($items, $model, &$components)
    {
        $items->each(function ($item) use (&$components, $model) {
            $item->component_type = $model;
            $item->component_nova = config("components.$model.nova");
            $item->component_image = asset(config("components.$model.image"));
            $components->push($item);
        });

        return $components;
    }
}
