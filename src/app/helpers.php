<?php

if (!function_exists('current_url_is')) {
    /**
     * @param string $urlToCompare
     *
     * @return bool
     */
    function current_url_is(string $urlToCompare): bool
    {
        // On récupère uniquement le path de l'url à comparer
        $urlPath = parse_url($urlToCompare, PHP_URL_PATH) ?? '';
        $urlPath = trim($urlPath, '/');

        return request()->is("$urlPath*");
    }
}

if (!function_exists('str_unslug')) {
    /**
     * @param string $string
     *
     * @return string
     */
    function str_unslug(string $string): string
    {
        // Supprime les séparateurs
        $string = preg_replace('/[-_.]/', ' ', $string);

        // Met les majuscules
        $string = ucfirst($string);

        return $string;
    }
}

if (!function_exists('has_zone_menu')) {
    /**
     * @param $zone
     *
     * @return bool
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function has_zone_menu($zone): bool
    {
        $menus =  app()->make(\Webid\Cms\Src\App\Repositories\Menu\MenuRepository::class);

        return $menus->menuZoneExist($zone);
    }
}

if (!function_exists('is_video')) {
    /**
     * @param $filename
     *
     * @return bool
     */
    function is_video($filename)
    {
        return !!preg_match('/^.*\.(mp4|mov)$/i', $filename);
    }
}

if (!function_exists('is_image')) {
    /**
     * @param $filename
     *
     * @return bool
     */
    function is_image($filename)
    {
        return !!preg_match('/^.*\.(jpg|png|gif|jpeg|tiff|)$/i', $filename);
    }
}

if (!function_exists('menu_builder')) {
    /**
     * @param $zoneId
     * @param $label
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function menu_builder($zoneId, $label)
    {
        $menuRepository = app(\Webid\Cms\Src\App\Repositories\Menu\MenuRepository::class);
        $menu = $menuRepository->getMenuByMenuZone($zoneId);

        if (!empty($menu)) {
            $data = \Webid\Cms\Src\App\Http\Resources\Menu\MenuResource::make($menu)->resolve();

            return view('components.menu')->with($data);
        }
    }
}
