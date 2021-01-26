<?php

namespace Webid\Cms\App\Services;

class DynamicResource
{
    /** @var array */
    private static $topLevelResources = [];

    /** @var array */
    private static $templateModuleGroupResources = [];

    /** @var array  */
    private static $singleModuleResources = [];

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
    public static function pushTemplateModuleGroupResource(array $resource): void
    {
        array_push(self::$templateModuleGroupResources, $resource);
    }

    /**
     * @return array
     */
    public static function getTemplateModuleGroupResources(): array
    {
        return self::$templateModuleGroupResources;
    }

    /**
     * @param array $resource
     */
    public static function pushSingleModuleResources(array $resource): void
    {
        array_push(self::$singleModuleResources, $resource);
    }

    /**
     * @return array
     */
    public static function getSingleModuleResources(): array
    {
        return self::$singleModuleResources;
    }
}
