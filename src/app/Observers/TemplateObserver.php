<?php

namespace Webid\Cms\App\Observers;

use App\Models\Template;
use Webid\Cms\App\Repositories\TemplateRepository;
use Illuminate\Support\Str;
use Webid\Cms\App\Services\LanguageService;

class TemplateObserver
{
    /**
     * Méthode appelée avant la sauvegarde d'une Template (création ou mise à jour)
     *
     * @param Template $template
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function saving(Template $template)
    {
        $repository = app()->make(TemplateRepository::class);
        $titles = $template->getTranslations('title');
        $slugArray = [];
        $slugCreationArray = [];
        $originalSlug = $template->getOriginal('slug') ?? [];
        $value = $template->getTranslations('slug') ?? [];
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
        foreach ($CheckSlugs as $language => $slug) {
            if ($slug) {
                try {
                    $repository->getBySlug($slug, $language);

                    $slugExisting = $repository->getLastCorrespondingSlugWithNumber($slug, $language);
                    if ($slugExisting) {
                        foreach (json_decode($slugExisting->attributes['slug'], true) as $temp_language => $temp_slug) {
                            if ($language == $temp_language) {
                                $number = explode('-', $temp_slug);
                                $newId = $number[1] + $count;
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

        // Enregistrement des slugs
        $template->slug = $allSlug;
    }

    /**
     * Récupération des codes langues utilisées dans le site
     *
     * @return array
     */
    protected function getUsedCodeLanguage()
    {
        $languages = app(LanguageService::class)->getUsedLanguage();
        return array_keys($languages);
    }
}
