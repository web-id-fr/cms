<?php

namespace Webid\Cms\App\Services\Sitemap;

/**
 * @property-read string $lang
 * @property-read string $path
 */
class SitemapUrlAlternate
{
    /** @var string */
    public $lang;

    /** @var string */
    public $path;

    /**
     * @param string $lang
     * @param string $path
     */
    public function __construct(string $lang, string $path)
    {
        $this->lang = $lang;
        $this->path = $path;
    }
}
