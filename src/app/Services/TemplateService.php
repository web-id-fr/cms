<?php

namespace Webid\Cms\Src\App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Webid\Cms\Src\App\Repositories\TemplateRepository;

class TemplateService
{
    /** @var TemplateRepository  */
    protected $templateRepository;

    /** @var LanguageService  */
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

            if (!empty($template)) {
                $allowed_locales = $this->languageService->getUsedLanguage();
                $data = $template->getTranslations('slug');

                $data = array_intersect_key($data, $allowed_locales);

                foreach ($allowed_locales as $locale => $locale_name) {
                    if (!Arr::has($data, $locale)) {
                        Arr::set($data, $locale, '');
                    }
                }

                ksort($data);

                return $data;
            } else {
                throw new ModelNotFoundException();
            }
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
}
