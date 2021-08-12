<?php

namespace Webid\Cms\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Webid\Cms\App\Repositories\TemplateRepository;

class RedirectionParentChild
{
    private TemplateRepository $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $slugs = explode('/', $path);
        $lastParam = end($slugs);
        $lang = reset($slugs);

        if (!array_key_exists($lang, config('translatable.locales'))) {
            abort(404);
        }

        URL::defaults(['lang' => $lang]);
        /** @var \App\Models\Template $template */
        $template = $this->templateRepository->getBySlug($lastParam, $lang);
        $fullPath = $template->getFullPath($lang);

        if ($path !== $fullPath) {
            return redirect("/$fullPath", 301);
        }

        return $next($request);
    }
}
