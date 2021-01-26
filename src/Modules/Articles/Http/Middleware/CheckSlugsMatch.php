<?php

namespace Webid\Cms\Modules\Articles\Http\Middleware;

use Illuminate\Http\Request;
use Webid\Cms\Modules\Articles\Helpers\SlugHelper;

class CheckSlugsMatch
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        if (SlugHelper::articleSlug($request->lang) !== $request->articles_slug) {
            abort(404);
        }

        if (isset($request->categories_slug)) {
            if (SlugHelper::articleCategorySlug($request->lang) !== $request->categories_slug) {
                abort(404);
            }
        }

        return $next($request);
    }
}
