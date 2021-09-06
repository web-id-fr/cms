<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Components\CodeSnippetComponent;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\App\Models\Components\NewsletterComponent;
use Webid\Cms\App\Repositories\Components\CodeSnippetComponentRepository;
use Webid\Cms\App\Repositories\Components\GalleryComponentRepository;
use Webid\Cms\App\Repositories\Components\NewsletterComponentRepository;

class ComponentsService
{
    /** @var Collection */
    private $allComponents;

    /** @var GalleryComponentRepository */
    private $galleryComponentRepository;

    /** @var NewsletterComponentRepository */
    private $newsletterComponentRepository;

    /** @var CodeSnippetComponentRepository */
    private $codeSnippetComponentRepository;

    public function getAllComponents(): Collection
    {
        if (!empty($this->allComponents)) {
            return $this->allComponents;
        }

        $this->allComponents = collect();

        $this->galleryComponentRepository = app(GalleryComponentRepository::class);
        $this->newsletterComponentRepository = app(NewsletterComponentRepository::class);
        $this->codeSnippetComponentRepository = app(CodeSnippetComponentRepository::class);

        $components = collect();
        $allGalleriesComponents = collect();
        $allNewsletterComponents = collect();
        $codeSnippetComponents = collect();

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
        $this->loadComponents(
            $this->codeSnippetComponentRepository->getPublishedComponents(),
            CodeSnippetComponent::class,
            $codeSnippetComponents
        );

        $components[config('components.' . GalleryComponent::class . '.title')] = $allGalleriesComponents;
        $components[config('components.' . NewsletterComponent::class . '.title')] = $allNewsletterComponents;
        $components[config('components.' . CodeSnippetComponent::class . '.title')] = $codeSnippetComponents;

        $this->allComponents = $components;
        return $this->allComponents;
    }

    private function loadComponents(Collection $publishComponent, string $model, Collection $allComponents): Collection
    {
        $allPublishComponents = $this->mapItems($publishComponent, $model);
        $allPublishComponents->each(function ($component) use (&$allComponents) {
            $allComponents->push($component);
        });

        return $allComponents;
    }

    private function mapItems(Collection $items, string $model): Collection
    {
        return $items->each(function ($item) use ($model) {
            $item->component_type = $model;
            $item->component_nova = config("components.$model.nova");
            $item->component_image = asset(config("components.$model.image"));
            return $item;
        });
    }
}
