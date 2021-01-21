<?php

namespace Webid\Cms\Modules\Articles\Repositories;

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
