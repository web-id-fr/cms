<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;
use Webid\Cms\App\Repositories\Components\GalleryComponentRepository;
use Webid\Cms\App\Repositories\Components\NewsletterComponentRepository;

class ComponentsService
{
    /** @var array<mixed> */
    private $allComponents = [];

    /** @var GalleryComponentRepository */
    private $galleryComponentRepository;

    /** @var NewsletterComponentRepository */
    private $newsletterComponentRepository;

    public function getAllComponents()
    {
        if (!empty($this->allComponents)) {
            return $this->allComponents;
        }

        $this->allComponents = [];

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

        $this->allComponents = $components;
        return $this->allComponents;
    }

    /**
     * @param Collection $items
     * @param string $model
     *
     * @return Collection
     */
    private function mapItems(Collection $items, string $model)
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
    private function loadComponents(Collection $publishComponent, string $model, Collection $allComponents)
    {
        $allPublishComponents = $this->mapItems($publishComponent, $model);
        $allPublishComponents->each(function ($component) use (&$allComponents) {
            $allComponents->push($component);
        });

        return $allComponents;
    }
}
