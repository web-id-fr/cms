<?php

namespace Webid\Cms\Modules\Articles\Observers;

use Webid\Cms\App\Observers\Traits\GenerateTranslatableSlugIfNecessary;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Repositories\ArticleRepository;

class ArticleObserver
{
    use GenerateTranslatableSlugIfNecessary;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->repository = $articleRepository;
    }

    /**
     * @param Article $article
     */
    public function saving(Article $article): void
    {
        $titles = $article->getTranslations('title');
        $originalSlug = $article->getOriginal('slug') ?? [];
        $value = $article->getTranslations('slug') ?? [];

        $allSlug = $this->generateMissingSlugs($originalSlug, $value, $titles);

        $article->slug = $allSlug;
    }
}
