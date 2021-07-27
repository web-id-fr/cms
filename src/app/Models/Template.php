<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Webid\Cms\App\Models\BaseTemplate;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;

class Template extends BaseTemplate
{
    /** @var Collection */
    public $component_items;

    public function galleryComponents(): MorphToMany
    {
        return $this->morphedByMany(GalleryComponent::class, 'component')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function newsletterComponents(): MorphToMany
    {
        return $this->morphedByMany(NewsletterComponent::class, 'component')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function chargeComponents(): void
    {
        $components = collect();

        $this->mapItems($this->galleryComponents, GalleryComponent::class, $components);
        $this->mapItems($this->newsletterComponents, NewsletterComponent::class, $components);

        $components = $components->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->component_items = $components;
    }

    protected function mapItems(Collection $items, string $model, Collection &$components): Collection
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
