<?php

namespace Webid\Cms\Src\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Webid\Cms\Src\App\Services\LanguageService;

class Language
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->lang;

        if ($locale === null) {
            return redirect(app(LanguageService::class)->getFromBrowser());
        } else {
            // Change la langue de l'application
            app()->setLocale($locale);
            // Ajoute une valeur par défaut au paramètre "lang" sur les routes du front
            URL::defaults(['lang' => $locale]);
        }

        return $next($request);
    }
}
