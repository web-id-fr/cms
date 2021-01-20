<?php

namespace Webid\Cms\Modules\Articles\Repositories;

use Webid\Cms\Modules\Articles\Models\ArticleTag;

class ArticleTagRepository
{
    /** @var ArticleTag */
    protected $model;

    public function __construct(ArticleTag $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $tagName
     * @param string $language
     * @return ArticleTag
     */
    public function getTagByName(string $tagName, string $language): ArticleTag
    {
        return $this->model
            ->whereRaw(
                "JSON_UNQUOTE(LOWER(JSON_EXTRACT(name, '$.{$language}'))) = ?",
                [strtolower($tagName)]
            )
            ->firstOrFail();
    }
}
