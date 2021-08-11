<?php

namespace Webid\Cms\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webid\Cms\App\Services\TemplateService;

class RedirectToHomepage
{
    private TemplateService $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = request()->path();
        $slugs = explode('/', $path);
        $lastSlug = end($slugs);

        if ($this->templateService->getHomepageSlug() == $lastSlug) {
            return redirect(route('home'), 301);
        }

        return $next($request);
    }
}
