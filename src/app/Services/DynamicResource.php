<?php

namespace Webid\Cms\App\Services;

class DynamicResource
{
    /** @var array */
    private static $topLevelResources = [];

    /** @var array  */
    private static $groupGroupModuleResource = [];

    /**
     * @param array $resource
     */
    public static function pushTopLevelResource(array $resource): void
    {
        array_push(self::$topLevelResources, $resource);
    }

    /**
     * @return array
     */
    public static function getTopLevelResources(): array
    {
        return self::$topLevelResources;
    }

    /**
     * @param array $resource
     */
    public static function pushGroupModuleResource(array $resource): void
    {
        array_push(self::$groupGroupModuleResource, $resource);
    }

    /**
     * @return array
     */
    public static function getGroupModuleResources(): array
    {
        return self::$groupGroupModuleResource;
    }
}