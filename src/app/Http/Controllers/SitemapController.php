<?php

namespace Webid\Cms\App\Http\Controllers;

use Illuminate\Http\Request;
use Webid\Cms\App\Services\SitemapGenerator;

class SitemapController extends BaseController
{
    /** @var SitemapGenerator */
    private $sitemap;

    public function __construct(SitemapGenerator $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    public function index(Request $request)
    {
        return $this->sitemap->generate()->toResponse($request);
    }
}
