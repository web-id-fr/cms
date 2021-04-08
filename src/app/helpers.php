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
     * Compare la chaine passée en paramètre avec l'url actuelle,
     * pour déterminer si la chaine correspond à la page actuelle
     *
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
     * Transforme un slug en une chaine de caractères "classique" avec espaces et majuscules
     *
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
     * Vérifie si l'id de menu donné existe en bdd
     *
     * @param $zone
     *
     * @return bool
     */
    function has_zone_menu($zone): bool
    {
        $menus = app()->make(\Webid\Cms\App\Repositories\Menu\MenuRepository::class);

        return $menus->menuZoneExist($zone);
    }
}

if (!function_exists('is_video')) {
    /**
     * Détermine si le fichier dont le nom est passé en paramètre est une vidéo
     *
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
     * Détermine si le fichier dont le nom est passé en paramètre est une image
     *
     * @param $filename
     *
     * @return bool
     */
    function is_image($filename)
    {
        return !!preg_match('/^.*\.(jpg|png|gif|jpeg|tiff|svg)$/i', $filename);
    }
}

if (!function_exists('media_full_url')) {
    /**
     * Retourne l'URL complète d'un fichier qui est stocké dans le filemanager ou dans le s3
     *
     * @param string|null $file_path
     *
     * @return string
     *
     */
    function media_full_url(?string $file_path): string
    {
        if (is_null($file_path)) {
            return '';
        }

        $file_path = ltrim($file_path, '/');

        return config('cms.image_path') . $file_path;
    }
}

if (!function_exists('arrayKeysAreLocales')) {
    function arrayKeysAreLocales(array $parameter): bool
    {
        return !empty(array_intersect_key(config('translatable.locales'), $parameter));
    }
}
