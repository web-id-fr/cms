<?php

namespace Webid\Cms\App\Services;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Webid\Cms\App\Repositories\TemplateRepository;

class SitemapGenerator
{
    /** @var TemplateRepository */
    protected $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function generate(): Sitemap
    {
        $sitemap = Sitemap::create();

        $pagesToAddList = $this->getPagesToAdd();

        foreach ($pagesToAddList as $pageToAdd) {
            $url = Url::create($pageToAdd['path'])
                ->setLastModificationDate($pageToAdd['updated_at'])
                ->setChangeFrequency('')
                ->setPriority(0);

            foreach ($pageToAdd['alternates'] as $alternate) {
                $url->addAlternate($alternate['path'], $alternate['lang']);
            }

            $sitemap->add($url);
        }

        return $sitemap;
    }

    private function getPagesToAdd(): array
    {
        $pagesToAddList = [];

        foreach ($this->templateRepository->getPublishedAndIndexedTemplates() as $template) {
            $translatedAttributes = $template->getTranslationsAttribute();

            foreach ($translatedAttributes['slug'] as $lang => $slug) {
                if ($template->homepage) {
                    $path = $lang;
                } else {
                    $path = "{$lang}/{$slug}";
                }

                $alternates = [];
                foreach ($translatedAttributes['slug'] as $alternateLang => $alternateSlug) {
                    if ($template->homepage) {
                        $alternatePath = $alternateLang;
                    } else {
                        $alternatePath = "{$alternateLang}/{$alternateSlug}";
                    }
                    $alternates[] = [
                        'lang' => $alternateLang,
                        'path' => $alternatePath,
                    ];
                }

                $pagesToAddList[] = [
                    'lang' => $lang,
                    'path' => $path,
                    'updated_at' => $template->updated_at,
                    'alternates' => $alternates,
                ];
            }
        }

        return $pagesToAddList;
    }
}
