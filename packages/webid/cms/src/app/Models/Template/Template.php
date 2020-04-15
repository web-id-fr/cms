<?php

namespace App\Models;

use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Models\Template as TemplateBase;

class Template extends TemplateBase
{
    /** @var $components_item */
    public $components_item;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function galleryComponents()
    {
        return $this->morphedByMany(GalleryComponent::class, 'component')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function chargeComponents()
    {
        $components = collect();
        $gallery_components = $this->galleryComponents;

        $gallery_components->each(function ($gallery_component) use (&$components) {
            $gallery_component->component_type = GalleryComponent::class;
            $gallery_component->component_nova = "/nova/resources/component1s";
            $gallery_component->component_image = asset('images/galleryComponent.png');
            $components->push($gallery_component);
        });

        $components = $components->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->components_item = $components;
    }

}
