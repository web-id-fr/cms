<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts\Preset;

use Webid\Cms\Modules\Articles\Nova\Layouts\MediaLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\UrlLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\Resolver\ArticleResolver;
use Webid\Cms\Modules\Articles\Nova\Layouts\ImageLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\SlideshowLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\TextImageLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\TextLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\TextVideoLayout;
use Webid\Cms\Modules\Articles\Nova\Layouts\VideoLayout;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class ArticlePreset extends Preset
{
    /**
     * @param Flexible $field
     *
     * @throws \Exception
     */
    public function handle(Flexible $field)
    {
        $field->hideFromIndex();
        $field->button(__('Add a section'));

        $field->addLayout(TextLayout::class);
        $field->addLayout(TextImageLayout::class);
        $field->addLayout(TextVideoLayout::class);
        $field->addLayout(ImageLayout::class);
        $field->addLayout(VideoLayout::class);
        $field->addLayout(SlideshowLayout::class);
        $field->addLayout(MediaLayout::class);
        $field->addLayout(UrlLayout::class);

        $field->resolver(ArticleResolver::class);
    }
}
