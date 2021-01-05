<?php

namespace Webid\Cms\App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webid\Cms\App\Services\SitemapGenerator;

class SitemapController extends BaseController
{
    /** @var SitemapGenerator */
    private $sitemap;

    public function __construct(SitemapGenerator $sitemap)
    {
        $this->sitemap = $sitemap;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->sitemap->generate()->toResponse($request);
    }
}
