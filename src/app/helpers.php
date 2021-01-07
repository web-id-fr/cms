<?php

if (!function_exists('package_base_path')) {
    /**
     * Retourne le chemin en partant de la racine du package
     *
     * @param string $path
     * @return string
     */
    function package_base_path(string $path = ''): string
    {
        $path = ltrim($path, '/');
        return __DIR__ . "/../../{$path}";
    }
}

if (!function_exists('package_module_path')) {
    /**
     * Retourne le chemin en partant du dossier Modules du package
     *
     * @param string $path
     * @return string
     */
    function package_module_path(string $path = ''): string
    {
        $path = ltrim($path, '/');
        return package_base_path("src/Modules/{$path}");
    }
}

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
        $menus =  app()->make(\Webid\Cms\App\Repositories\Menu\MenuRepository::class);

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
        $menuRepository = app(\Webid\Cms\App\Repositories\Menu\MenuRepository::class);
        $menu = $menuRepository->getMenuByMenuZone($zoneId);

        if (!empty($menu)) {
            $data = \Webid\Cms\App\Http\Resources\Menu\MenuResource::make($menu)->resolve();

            return view('components.menu')->with($data);
        }
    }
}
