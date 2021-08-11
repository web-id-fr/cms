<?php

namespace Webid\Cms\App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Webid\Cms\App\Models\BaseTemplate;
use Webid\Cms\App\Repositories\TemplateRepository;

class TemplateService
{
    /** @var TemplateRepository */
    protected $templateRepository;

    /** @var LanguageService */
    protected $languageService;

    /**
     * @param TemplateRepository $templateRepository
     * @param LanguageService $languageService
     */
    public function __construct(TemplateRepository $templateRepository, LanguageService $languageService)
    {
        $this->templateRepository = $templateRepository;
        $this->languageService = $languageService;
    }

    /**
     * @param string $slug
     *
     * @return array
     */
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

    /**
     * @return string
     */
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

    /**
     * @param BaseTemplate $template
     * @param array $queryParams
     * @return string
     */
    public function getCanonicalUrlFor(BaseTemplate $template, array $queryParams): string
    {
        $routeParams = [];
        $routename = 'home';

        if ($template->containsArticlesList() && isset($queryParams['category'])) {
            $routeParams['category'] = $queryParams['category'];
        }

        if (!$template->isHomepage()) {
            /** @var string $slug */
            $slug = $template->getFullPath(request()->lang);
            return $slug;
        }

        return route($routename, $routeParams);
    }
}
