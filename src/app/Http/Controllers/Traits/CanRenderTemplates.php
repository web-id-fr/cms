<?php

namespace Webid\Cms\Src\App\Traits;

use Webid\Cms\Src\App\Services\LanguageService;

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
            })
            ->toArray();
    }
}
