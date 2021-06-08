<?php

namespace Webid\Cms\App\Models\Traits;

use Illuminate\Support\Collection as BaseCollection;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible as HasFlexibleBase;
use function Safe\json_decode;

trait HasFlexible
{
    use HasFlexibleBase {
        toFlexible as toFlexibleBase;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function toFlexible($value)
    {
        if (request()->is('nova-api*')) {
            return $value;
        }

        return $this->toFlexibleBase($value);
    }

    /**
     * @override
     * @param mixed $value
     *
     * @return array|null
     *
     * @throws \Safe\Exceptions\JsonException
     */
    protected function getFlexibleArrayFromValue($value): ?array
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
            return is_array($value) ? $value : null;
        }

        if (is_a($value, BaseCollection::class)) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return $value;
        }

        return null;
    }
}
