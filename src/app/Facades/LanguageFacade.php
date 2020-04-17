<?php

namespace Webid\Cms\Src\App\Facades;

use Webid\LanguageTool\Models\Language;
use Webid\LanguageTool\Repositories\LanguageRepository;
use Illuminate\Support\Facades\Facade;

class LanguageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'language';
    }

    /**
     * Retourne les langues utilisées par le site et dans un format utilisable par le champ Translatable
     *
     * @return array
     */
    public static function getUsedLanguage()
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
     * Retourne le slug du language par défaut du navigateur
     *
     * @return string
     */
    public static function getBrowserDefault()
    {
        $browserLanguage = request()->server('HTTP_ACCEPT_LANGUAGE');

        return substr($browserLanguage, 0, 2) ?: '';
    }

    /**
     * Définit le language à partir du navigateur de l'utilisateur
     *
     * @return bool|\Illuminate\Config\Repository|mixed|string
     */
    public static function getFromBrowser()
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
    public static function exists(string $lang)
    {
        return in_array($lang, static::getUsedLanguagesSlugs());
    }

    /**
     * Retourne les langues utilisées par le site au format ["fr", "en", "...]
     *
     * @return array
     */
    public static function getUsedLanguagesSlugs(): array
    {
        return array_keys(static::getUsedLanguage());
    }

    /**
     * Retourne les langues utilisées par le site et dans un format utilisable dans une regex
     * Ex.: "fr|en"
     *
     * @return string
     */
    public static function getUsedLanguagesAsRegex(): string
    {
        return join('|', static::getUsedLanguagesSlugs());
    }

    /**
     * Retourne les langues disponibles (activées ou non) dans un format utilisable dans une regex
     * EX.: "fr|en|it|es"
     *
     * @return string
     */
    public static function getAllLanguagesAsRegex(): string
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
    public static function isRTL($lang = null)
    {
        if (empty($lang)) {
            $lang = app()->getLocale();
        }

        $rtlLocales = config('translatable.rtl_locales', []);

        return in_array($lang, $rtlLocales);
    }
}
