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
        if ($this->templateService->getHomepageSlug() == $request->slug) {
            return redirect(route('home'), 301);
        }

        return $next($request);
    }
}
