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
     * @param string $language
     * @return Collection<Article>
     */
    public function getPublishedArticles(string $language): Collection
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
}
