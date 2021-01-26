<?php

namespace Webid\Cms\Modules\Articles\Helpers;

class SlugHelper
{
    /**
     * @param string $lang
     * @return string
     */
    public static function articleSlug(string $lang): string
    {
        $value = nova_get_setting('articles_root_slug');

        if (!is_array($value)) {
            $value = json_decode($value, true) ?? [];
        }

        return $value[$lang] ?? config('articles.default_paths.articles');
    }

    /**
     * @param string $lang
     * @return string
     */
    public static function articleCategorySlug(string $lang): string
    {
        $value = nova_get_setting('articles_categories_root_slug');

        if (!is_array($value)) {
            $value = json_decode($value, true) ?? [];
        }

        return $value[$lang] ?? config('articles.default_paths.categories');
    }
}
