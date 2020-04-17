<?php

namespace Webid\Cms\Src\App\Traits;

use Webid\Cms\Src\App\Facades\LanguageFacade;

trait CanRenderTemplates
{
    /**
     * @return array
     */
    protected function getAvailableLanguages(): array
    {
        return collect(LanguageFacade::getUsedLanguage())
            ->map(function ($value, $key) {
                return app()->getLocale() == $key;
            })
            ->toArray();
    }
}
