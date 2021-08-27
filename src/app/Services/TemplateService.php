<?php

namespace Webid\Cms\App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Webid\Cms\App\Models\BaseTemplate;
use Webid\Cms\App\Repositories\TemplateRepository;

class TemplateService
{
    protected TemplateRepository $templateRepository;
    protected LanguageService $languageService;

    public function __construct(TemplateRepository $templateRepository, LanguageService $languageService)
    {
        $this->templateRepository = $templateRepository;
        $this->languageService = $languageService;
    }

    public function getUrlsForPage(string $slug): array
    {
        try {
            $template = $this->templateRepository->getBySlug($slug, app()->getLocale());
            $allowed_locales = $this->languageService->getUsedLanguage();
            $data = $template->getTranslations('slug');
            $data = array_intersect_key($data, $allowed_locales);

            /**
             * @var string $locale
             * @var string $locale_name
             */
            foreach ($allowed_locales as $locale => $locale_name) {
                if (!Arr::has($data, $locale)) {
                    Arr::set($data, $locale, '');
                }
            }

            ksort($data);

            return $data;
        } catch (ModelNotFoundException $exception) {
            return [];
        }
    }

    public function getHomepageSlug(): string
    {
        try {
            $template = $this->templateRepository->getSlugForHomepage();

            if (empty($template)) {
                return '';
            }

            return $template->slug;
        } catch (ModelNotFoundException $exception) {
            return '';
        }
    }

    public function getCanonicalUrlFor(BaseTemplate $template, array $queryParams, string $language = null): string
    {
        $routeParams = [];
        $routeName = 'home';

        if ($template->containsArticlesList() && isset($queryParams['category'])) {
            $routeParams['category'] = $queryParams['category'];
        }

        if (!empty($template->reference_page_id)) {
            $reference_page = $this->templateRepository->getById($template->reference_page_id);
            return url($reference_page->getFullPath($language ?? request()->lang));
        }

        if (!$template->isHomepage()) {
            return url($template->getFullPath($language ?? request()->lang));
        }

        return route($routeName, $routeParams);
    }
}
