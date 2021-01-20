<?php

namespace Webid\Cms\Modules\Articles\Tests\Helpers;

use Webid\Cms\Modules\Articles\Models\ArticleTag;

trait ArticleTagCreator
{
    protected function createArticleTag(array $parameters = []): ArticleTag
    {
        return ArticleTag::factory($parameters)->create();
    }
}
