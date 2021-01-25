<?php

namespace Webid\Cms\Modules\Articles\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Webid\Cms\Modules\Articles\Helpers\SlugHelper;

class SetDefaultSlugs
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        URL::defaults([
            'articles_slug' => SlugHelper::articleSlug(app()->getLocale()),
            'categories_slug' => SlugHelper::articleCategorySlug(app()->getLocale()),
        ]);

        return $next($request);
    }
}
