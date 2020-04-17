<?php

namespace Webid\Cms\Src\App\Http\Middleware;

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
        if (!array_key_exists($request->route('lang'), config('translatable.locales'))) {
            abort('404');
        }

        return $next($request);
    }
}
