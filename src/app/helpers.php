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
        $menus =  app()->make(\Webid\Cms\App\Repositories\Menu\MenuRepository::class);

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
        return !!preg_match('/^.*\.(jpg|png|gif|jpeg|tiff|)$/i', $filename);
    }
}

if (!function_exists('bladeCompile')) {
    /**
     * Prend une chaine contenant un fragment de template Blade en paramètre, et retourne le résultat
     * construit avec les valeurs contenues dans $args
     *
     * @param       $value
     * @param array $args
     *
     * @return false|string
     * @throws Exception
     */
    function bladeCompile($value, array $args = [])
    {
        $generated = \Illuminate\Support\Facades\Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);

        try {
            // We'll include the view contents for parsing within a catcher
            // so we can avoid any WSOD errors. If an exception occurs we
            // will throw it out to the exception handler.
            eval('?>' . $generated);
        } catch (Exception $e) {
            // If we caught an exception, we'll silently flush the output
            // buffer so that no partially rendered views get thrown out
            // to the client and confuse the user with junk.
            ob_get_clean();
            throw $e;
        }

        $content = ob_get_clean();

        return $content;
    }
}
