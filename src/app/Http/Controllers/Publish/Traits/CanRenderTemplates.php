<?php

namespace App\Http\Controllers\Traits;

use Webid\Cms\Src\App\Services\LanguageService;
use Webid\Cms\Src\App\Services\TemplateService;

trait CanRenderTemplates
{
    /**
     * @return array
     */
    protected function getAvailableLanguages(): array
    {
        return collect(app(LanguageService::class)->getUsedLanguage())
            ->map(function ($value, $key) {
                return app()->getLocale() == $key;
            })->toArray();
    }

    /**
     * @param string $slug
     *
     * @return array
     */
    protected function getUrlsForPage(string $slug): array
    {
        return app(TemplateService::class)->getUrlsForPage($slug);
    }
}
