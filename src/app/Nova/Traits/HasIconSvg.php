<?php

namespace Webid\Cms\App\Nova\Traits;

trait HasIconSvg
{
    /**
     * @param string $iconName
     * @param string|null $iconPath
     * @return string
     * @throws \Exception
     */
    protected static function svgIcon(string $iconName, string $iconPath = null): string
    {
        if (empty($iconPath)) {
            $iconPath = resource_path('views/svg');
        }

        $iconPath = rtrim($iconPath, '/');
        $iconFullName = "{$iconPath}/{$iconName}.svg";

        if (!file_exists($iconFullName)) {
            throw new \Exception("Icon file {$iconFullName} does not exist.");
        }

        return file_get_contents($iconFullName);
    }
}
