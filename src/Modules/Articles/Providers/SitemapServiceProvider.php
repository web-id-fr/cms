<?php

namespace Webid\Cms\Modules\Articles\Providers;

use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Webid\Cms\App\Repositories\TemplateRepository;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\App\Services\Sitemap\SitemapGenerator;
use Webid\Cms\App\Services\Sitemap\SitemapUrl;
use Webid\Cms\App\Services\Sitemap\SitemapUrlAlternate;
use Webid\Cms\Modules\Articles\Models\ArticleCategory;
use Webid\Cms\Modules\Articles\Repositories\ArticleCategoryRepository;
use Webid\Cms\Modules\Articles\Repositories\ArticleRepository;

class SitemapServiceProvider extends ServiceProvider
{
    protected SitemapGenerator $sitemap;
    protected ArticleRepository $articleRepository;
    protected ArticleCategoryRepository $categoryRepository;
    protected TemplateRepository $templateRepository;

    /**
     * @param SitemapGenerator $sitemap
     * @param ArticleRepository $articleRepository
     * @param ArticleCategoryRepository $categoryRepository
     * @param LanguageService $languageService
     * @param TemplateRepository $templateRepository
     * @return void
     */
    public function boot(
        SitemapGenerator $sitemap,
        ArticleRepository $articleRepository,
        ArticleCategoryRepository $categoryRepository,
        LanguageService $languageService,
        TemplateRepository $templateRepository
    ) {
        $this->sitemap = $sitemap;
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->templateRepository = $templateRepository;

        $this->sitemap->addCallback(function () use ($languageService) {
            return $this->registerSitemapableUrls(
                $languageService->getUsedLanguagesSlugs()
            );
        });
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    protected function registerSitemapableUrls(array $usedLangs): array
    {
        return array_merge(
            $this->registerArticlesPages($usedLangs),
            $this->registerCategoriesPages($usedLangs)
        );
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    private function registerArticlesPages(array $usedLangs): array
    {
        $urls = [];
        $alternates = [];

        foreach ($this->articleRepository->getPublishedArticles() as $article) {
            $slugs = $article->getTranslations('slug');

            foreach ($usedLangs as $lang) {
                if (!isset($slugs[$lang])) {
                    continue;
                }

                $path = route('articles.show', [
                    'lang' => $lang,
                    'slug' => $slugs[$lang],
                ]);

                $urls[$article->id][] = new SitemapUrl(
                    $path,
                    $article->updated_at
                );
                $alternates[$article->id][] = new SitemapUrlAlternate($lang, $path);
            }

            foreach ($urls as $article_id => $urls_for_article) {
                foreach ($urls_for_article as $url) {
                    $url->setAlternates($alternates[$article_id]);
                }
            }
        }

        return Arr::flatten($urls);
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    private function registerCategoriesPages(array $usedLangs): array
    {
        $urls = [];

        $pagesContainingArticlesLists = $this->templateRepository->getPublicPagesContainingArticlesLists();

        foreach ($pagesContainingArticlesLists as $page) {
            foreach ($this->categoryRepository->all() as $category) {
                $urls = array_merge(
                    $urls,
                    $this->generateUrlsForPageAndCategory($usedLangs, $page, $category)
                );
            }
        }

        return $urls;
    }

    /**
     * @param array $usedLangs
     * @param Template $page
     * @param ArticleCategory $category
     * @return array
     */
    private function generateUrlsForPageAndCategory(array $usedLangs, Template $page, ArticleCategory $category): array
    {
        $urls = [];
        $alternates = [];

        $slugs = $page->getTranslations('slug');
        $names = $category->getTranslations('name');

        foreach ($usedLangs as $lang) {
            if (!isset($names[$lang]) || !isset($slugs[$lang])) {
                continue;
            }

            if ($page->isHomepage()) {
                $path = route('home', [
                    'lang' => $lang,
                    'category' => $names[$lang],
                ]);
            } else {
                $path = get_full_url_for_page("$slugs[$lang]?$names[$lang]");
            }

            $urls[] = new SitemapUrl(
                $path,
                $category->updated_at
            );
            $alternates[] = new SitemapUrlAlternate($lang, $path);
        }

        foreach ($urls as $url) {
            $url->setAlternates($alternates);
        }

        return $urls;
    }
}
