<?php

namespace Webid\Cms\Src\App\Http\Middleware;

use Webid\Cms\Src\App\Facades\LanguageFacade;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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
            return redirect(LanguageFacade::getFromBrowser());
        } else {
            // Change la langue de l'application
            app()->setLocale($locale);
            // Ajoute une valeur par défaut au paramètre "lang" sur les routes du front
            URL::defaults(['lang' => $locale]);
        }

        return $next($request);
    }
}
