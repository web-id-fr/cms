<?php

namespace Webid\Cms\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Webid\Cms\App\Services\LanguageService;

class Language
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $explodedPath = array_filter(explode('/', $request->path()));

        $locale = $request->lang
            ?? array_shift($explodedPath)
            ?? null;

        if ($locale === null) {
            return redirect(app(LanguageService::class)->getFromBrowser());
        } else {
            app()->setLocale($locale);
            URL::defaults(['lang' => $locale]);
        }

        return $next($request);
    }
}
