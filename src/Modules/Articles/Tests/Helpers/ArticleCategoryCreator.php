<?php

namespace Webid\Cms\Modules\Articles\Tests\Helpers;

use Webid\Cms\Modules\Articles\Models\ArticleCategory;

trait ArticleCategoryCreator
{
    protected function createArticleCategory(array $parameters = []): ArticleCategory
    {
        return ArticleCategory::factory($parameters)->create();
    }
}
