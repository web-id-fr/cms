<?php

namespace App\Models;

use Webid\Cms\Src\App\Models\Components\GalleryComponent;
use Webid\Cms\Src\App\Models\Components\NewsletterComponent;
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

        $gallery_components->each(function ($gallery_component) use (&$components) {
            $gallery_component->component_type = GalleryComponent::class;
            $gallery_component->component_nova = "/nova/resources/gallery-components";
            $gallery_component->component_image = asset('/images/components/gallery_component.png');
            $components->push($gallery_component);
        });
        $newsletter_components->each(function ($newsletter_component) use (&$components) {
            $newsletter_component->component_type = NewsletterComponent::class;
            $newsletter_component->component_nova = "/nova/resources/newsletter-components";
            $newsletter_component->component_image = asset('/images/components/newsletter_component.png');
            $components->push($newsletter_component);
        });

        $components = $components->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->components_item = $components;
    }

}
