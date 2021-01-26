<?php

namespace Webid\Cms\App\Services\Sitemap;

class SitemapUrlCollection
{
    /** @var SitemapUrl[] */
    private array $urls;

    public function __construct()
    {
        $this->urls = [];
    }

    /**
     * @param SitemapUrl $url
     */
    public function push(SitemapUrl $url): void
    {
        array_push($this->urls, $url);
    }

    /**
     * @return SitemapUrl[]
     */
    public function all(): array
    {
        return $this->urls;
    }
}
