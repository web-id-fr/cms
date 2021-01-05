<?php

namespace Webid\Cms\App\Services;

class DynamicResource
{
    /** @var array */
    private static $topLevelResources = [];

    public static function pushTopLevelResource(array $resource): void
    {
        array_push(self::$topLevelResources, $resource);
    }

    public static function getTopLevelResources(): array
    {
        return self::$topLevelResources;
    }
}
