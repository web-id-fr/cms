<?php

namespace Webid\Cms\Modules\Articles\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Cms\Modules\Articles\Models\Article;

class ArticleRepository
{
    /** @var Article */
    protected $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<Article>
     */
    public function getPublishedArticles(): Collection
    {
        return $this->model
            ->published()
            ->orderBy('order')
            ->get();
    }

    /**
     * @return Collection<Article>
     */
    public function getPublishedAndAllowOnListArticles(): Collection
    {
        return $this->model
            ->published()
            ->where('not_display_in_list', false)
            ->orderBy('order')
            ->get();
    }


    /**
     * @param string $language
     * @return Collection<Article>
     */
    public function getPublishedArticlesForLang(string $language): Collection
    {
        return $this->model
            ->publishedForLang($language)
            ->get();
    }

    /**
     * @param string $slug
     * @param string $language
     * @return Article
     */
    public function getBySlug(string $slug, string $language): Article
    {
        $slug = strtolower($slug);

        return $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug\"")
            ->publishedForLang($language)
            ->firstOrFail()
            ->load('categories');
    }

    /**
     * @param string $slug
     * @param string $language
     * @return Article|null
     */
    public function getLastCorrespondingSlugWithNumber(string $slug, string $language): ?Article
    {
        $slug = strtolower($slug);
        return $this->model
            ->where('slug', 'regexp', "\"$language\"[ ]*:[ ]*\"$slug(-[1-9])\"")
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * @return Article|null
     */
    public function latestUpdatedPublishedArticle(): ?Article
    {
        return $this->model
            ->published()
            ->orderByDesc(Article::UPDATED_AT)
            ->first();
    }

    /**
     * @return Collection<Article>
     */
    public function getPublishedPressArticles(): Collection
    {
        return $this->model
            ->published()
            ->where('article_type', Article::_TYPE_PRESS)
            ->orderBy('order')
            ->get();
    }

    /**
     * @return Collection<Article>
     */
    public function getPublishedNormalArticles(): Collection
    {
        return $this->model
            ->published()
            ->where('article_type', Article::_TYPE_NORMAL)
            ->orderBy('order')
            ->get();
    }

    /**
     * @param Article $article
     * @param int $limit
     *
     * @return Collection<Article>
     */
    public function getXRelatedArticles(Article $article, int $limit): Collection
    {
        return $this->model
            ->published()
            ->whereHas('categories', function ($query) use ($article) {
                $query->whereIn('id', $article->categories->pluck('id'));
            })
            ->where('id', '!=', $article->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
