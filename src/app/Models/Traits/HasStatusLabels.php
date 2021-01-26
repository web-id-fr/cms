<?php

namespace Webid\Cms\App\Models\Traits;

trait HasStatusLabels
{
    /**
     * @return array
     */
    public static function statusLabels(): array
    {
        return [
            self::_STATUS_PUBLISHED => __('Published'),
            self::_STATUS_DRAFT => __('Draft'),
        ];
    }
}
