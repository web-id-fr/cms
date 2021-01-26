<?php

namespace Webid\Cms\App\Services\Sitemap;

use DateTime;

/**
 * @property-read string $path
 * @property-read DateTime $updated_at
 * @property-read SitemapUrlAlternate[] $alternates
 */
class SitemapUrl
{
    /** @var string */
    public string $path;

    /** @var DateTime */
    public DateTime $updated_at;

    /** @var SitemapUrlAlternate[] */
    public array $alternates;

    /**
     * @param string $path
     * @param DateTime $updated_at
     * @param SitemapUrlAlternate[] $alternates
     */
    public function __construct(string $path, DateTime $updated_at, array $alternates = [])
    {
        $this->path = $path;
        $this->updated_at = $updated_at;
        $this->alternates = $alternates;
    }

    /**
     * @param SitemapUrlAlternate[] $alternates
     */
    public function setAlternates(array $alternates): void
    {
        $this->alternates = $alternates;
    }
}
