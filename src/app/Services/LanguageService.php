<?php

namespace Webid\Cms\App\Services;

use Webid\LanguageTool\Models\Language;
use Webid\LanguageTool\Repositories\LanguageRepository;
use function Safe\substr;

class LanguageService
{
    /**
     * Retourne les langues utilisées par le site et dans un format utilisable par le champ Translatable
     *
     * @return array
     */
    public function getUsedLanguage()
    {
        $languageRepository = new LanguageRepository(new Language());
        $languages = $languageRepository->all();
        $allPossible = [];

        $languages->each(function ($language) use (&$allPossible) {
            $allPossible[Language::getLocalByFlag($language->flag)] = $language->name;
        });

        return $allPossible;
    }

    /**
     * @return string
     *
     * @throws \Safe\Exceptions\StringsException
     */
    public function getBrowserDefault()
    {
        /** @var string $browserLanguage */
        $browserLanguage = request()->server('HTTP_ACCEPT_LANGUAGE');

        return substr($browserLanguage, 0, 2) ?: '';
    }

    /**
     * @return \Illuminate\Config\Repository|mixed|string
     *
     * @throws \Safe\Exceptions\StringsException
     */
    public function getFromBrowser()
    {
        $browserLanguage = self::getBrowserDefault();

        if (self::exists($browserLanguage)) {
            return $browserLanguage;
        }

        return config('app.locale');
    }

    /**
     * Retourne si le language $lang existe
     *
     * @param string $lang
     *
     * @return bool
     */
    public function exists(string $lang)
    {
        return in_array($lang, static::getUsedLanguagesSlugs());
    }

    /**
     * Retourne les langues utilisées par le site au format ["fr", "en", "...]
     *
     * @return array
     */
    public function getUsedLanguagesSlugs(): array
    {
        return array_keys(static::getUsedLanguage());
    }

    /**
     * Retourne les langues utilisées par le site et dans un format utilisable dans une regex
     * Ex.: "fr|en"
     *
     * @return string
     */
    public function getUsedLanguagesAsRegex(): string
    {
        return join('|', static::getUsedLanguagesSlugs());
    }

    /**
     * Retourne les langues disponibles (activées ou non) dans un format utilisable dans une regex
     * EX.: "fr|en|it|es"
     *
     * @return string
     */
    public function getAllLanguagesAsRegex(): string
    {
        return join('|', array_keys(config('translatable.locales') ?? []));
    }

    /**
     * Détermine si la langue passée / la langue actuelle est RTL
     *
     * @param string|null $lang
     *
     * @return bool
     */
    public function isRTL($lang = null)
    {
        if (empty($lang)) {
            $lang = app()->getLocale();
        }

        $rtlLocales = config('translatable.rtl_locales', []);

        return in_array($lang, $rtlLocales);
    }
}
