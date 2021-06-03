<?php

namespace Webid\Cms\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLanguageExist
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var string $lang */
        $lang = $request->route('lang');
        if (!array_key_exists($lang, config('translatable.locales'))) {
            abort(404);
        }

        return $next($request);
    }
}
