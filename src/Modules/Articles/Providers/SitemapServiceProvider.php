<?php

namespace Webid\Cms\Modules\Articles\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\App\Services\Sitemap\SitemapGenerator;
use Webid\Cms\App\Services\Sitemap\SitemapUrl;
use Webid\Cms\App\Services\Sitemap\SitemapUrlAlternate;
use Webid\Cms\Modules\Articles\Helpers\SlugHelper;
use Webid\Cms\Modules\Articles\Repositories\ArticleCategoryRepository;
use Webid\Cms\Modules\Articles\Repositories\ArticleRepository;

class SitemapServiceProvider extends ServiceProvider
{
    /** @var SitemapGenerator */
    protected SitemapGenerator $sitemap;

    /** @var ArticleRepository */
    protected ArticleRepository $articleRepository;

    /** @var ArticleCategoryRepository */
    protected ArticleCategoryRepository $categoryRepository;

    public function boot(
        SitemapGenerator $sitemap,
        ArticleRepository $articleRepository,
        ArticleCategoryRepository $categoryRepository,
        LanguageService $languageService
    ) {
        $this->sitemap = $sitemap;
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;

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
            $this->registerArticlesIndex($usedLangs),
            $this->registerArticlesPages($usedLangs),
            $this->registerCategoriesPages($usedLangs)
        );
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    private function registerArticlesIndex(array $usedLangs): array
    {
        $urls = [];
        $alternates = [];

        foreach ($usedLangs as $lang) {
            $path = route('articles.index', ['lang' => $lang, 'articles_slug' => SlugHelper::articleSlug($lang)]);
            $latestUpdatedArticle = $this->articleRepository->latestUpdatedPublishedArticle();

            $urls[] = new SitemapUrl(
                $path,
                $latestUpdatedArticle->updated_at ?? Carbon::now()
            );
            $alternates[] = new SitemapUrlAlternate($lang, $path);
        }

        foreach ($urls as $url) {
            $url->setAlternates($alternates);
        }

        return $urls;
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    private function registerArticlesPages(array $usedLangs): array
    {
        $urls = [];

        foreach ($this->articleRepository->getPublishedArticles() as $article) {
            $slugs = $article->getTranslations('slug');

            $alternates = [];

            foreach ($usedLangs as $lang) {
                if (!isset($slugs[$lang])) {
                    continue;
                }

                $path = route('articles.show', [
                    'lang' => $lang,
                    'articles_slug' => SlugHelper::articleSlug($lang),
                    'slug' => $slugs[$lang],
                ]);

                $urls[] = new SitemapUrl(
                    $path,
                    $article->updated_at
                );
                $alternates[] = new SitemapUrlAlternate($lang, $path);
            }

            foreach ($urls as $url) {
                $url->setAlternates($alternates);
            }
        }

        return $urls;
    }

    /**
     * @param string[] $usedLangs
     * @return array
     */
    private function registerCategoriesPages(array $usedLangs): array
    {
        $urls = [];

        foreach ($this->categoryRepository->all() as $category) {
            $names = $category->getTranslations('name');

            $alternates = [];

            foreach ($usedLangs as $lang) {
                if (!isset($names[$lang])) {
                    continue;
                }

                $path = route('articles.categories.show', [
                    'lang' => $lang,
                    'articles_slug' => SlugHelper::articleSlug($lang),
                    'categories_slug' => SlugHelper::articleCategorySlug($lang),
                    'category' => $names[$lang],
                ]);

                $urls[] = new SitemapUrl(
                    $path,
                    $category->updated_at
                );
                $alternates[] = new SitemapUrlAlternate($lang, $path);
            }

            foreach ($urls as $url) {
                $url->setAlternates($alternates);
            }
        }

        return $urls;
    }
}
