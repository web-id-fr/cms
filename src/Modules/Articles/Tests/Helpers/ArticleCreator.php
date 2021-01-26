<?php

namespace Webid\Cms\Modules\Articles\Tests\Helpers;

use Webid\Cms\Modules\Articles\Models\Article;

trait ArticleCreator
{
    protected function createArticle(array $parameters = []): Article
    {
        return Article::factory($parameters)->create();
    }

    protected function createPublishedArticle(array $parameters = []): Article
    {
        $parameters = array_merge($parameters, [
            'publish_at' => null,
            'status' => Article::_STATUS_PUBLISHED,
        ]);

        return Article::factory($parameters)->create();
    }
}
