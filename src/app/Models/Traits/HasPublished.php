<?php

namespace Webid\Cms\App\Models\Traits;

trait HasPublished
{
    public function isPublished(): bool
    {
        return $this->status == self::_STATUS_PUBLISHED;
    }
}
