<?php

namespace Webid\Cms\Modules\Articles\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Cms\Modules\Articles\Models\ArticleCategory;

class ArticleCategoryRepository
{
    /** @var ArticleCategory */
    protected $model;

    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<ArticleCategory>
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @return Collection<ArticleCategory>
     */
    public function getCategoriesWithArticles(): Collection
    {
        return $this->model->whereHas('articles')->get();
    }

    /**
     * @param string $categoryName
     * @param string $language
     * @return ArticleCategory
     */
    public function getCategoryByName(string $categoryName, string $language): ArticleCategory
    {
        return $this->model
            ->whereRaw(
                "JSON_UNQUOTE(LOWER(JSON_EXTRACT(name, '$.{$language}'))) = ?",
                [strtolower($categoryName)]
            )
            ->firstOrFail();
    }
}
