<?php

namespace Webid\Cms\App\Observers\Traits;

use Illuminate\Support\Str;
use Webid\Cms\App\Repositories\TemplateRepository;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\Modules\Articles\Repositories\ArticleRepository;

trait GenerateTranslatableSlugIfNecessary
{
    /** @var TemplateRepository|ArticleRepository $repository */
    protected $repository;

    /**
     * @param array $originalSlug
     * @param array $value
     * @param array $titles
     * @return array
     */
    protected function generateMissingSlugs(array $originalSlug, array $value, array $titles): array
    {
        $slugArray = [];
        $slugCreationArray = [];
        $slugUpdating = array_diff_assoc($value, $originalSlug);
        $count = 1;

        // Enregistrement dans un tableau des langues pour les slugs enregistrés
        foreach ($value as $language => $slug) {
            array_push($slugArray, $language);
        }

        // Slugs manquant
        $missingSlugs = array_diff($this->getUsedCodeLanguage(), $slugArray);

        // Création des slugs manquant
        foreach ($missingSlugs as $missingSlug) {
            $slugCreationArray[$missingSlug] = Str::slug($titles[$missingSlug] ?? '');
        }

        // Création des slugs si suppression du slug lors de la mise à jour de la page
        if (in_array(null, $slugUpdating)) {
            foreach ($slugUpdating as $key => $slug) {
                $value[$key] = Str::slug($titles[$key] ?? '');
            }
        }

        // Création du nouveau tableau des slugs
        $allSlug = array_merge($slugCreationArray, $value);

        // Slugs à vérifier s'ils sont uniques
        $CheckSlugs = array_diff_assoc($allSlug, $originalSlug);

        // Vérification si les slugs sont uniques
        /**
         * @var string $language
         * @var string $slug
         */
        foreach ($CheckSlugs as $language => $slug) {
            if ($slug) {
                try {
                    $this->repository->getBySlug($slug, $language);

                    $slugExisting = $this->repository->getLastCorrespondingSlugWithNumber($slug, $language);
                    if ($slugExisting) {
                        foreach (json_decode($slugExisting->attributes['slug'], true) as $temp_language => $temp_slug) {
                            if ($language == $temp_language) {
                                $number = explode('-', $temp_slug);
                                $newId = intval($number[1]) + $count;
                                $allSlug[$language] = $slug . '-' . $newId;
                            }
                        }
                    } else {
                        $allSlug[$language] = $slug . '-' . $count;
                    }
                } catch (\Exception $exception) {
                    // Ignore & continue ...
                }
            }
        }

        // Enregistrement des slugs en minuscule
        foreach ($allSlug as $language => $slug) {
            $allSlug[$language] = strtolower($slug);
        }

        return $allSlug;
    }

    /**
     * @return array
     */
    private function getUsedCodeLanguage(): array
    {
        $languages = app(LanguageService::class)->getUsedLanguage();
        return array_keys($languages);
    }
}
