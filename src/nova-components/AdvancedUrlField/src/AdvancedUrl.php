<?php

namespace Webid\AdvancedUrlField;

use Laravel\Nova\Fields\Field;

class AdvancedUrl extends Field
{
    /** @var string */
    public $component = 'advanced-url';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));

        $this->withMeta([
            'locales' => $locales,
        ]);
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param mixed $resource
     * @param string $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        if (method_exists((object)$resource, 'getTranslations')) {
            return $resource->getTranslations($attribute);
        }
        return data_get($resource, $attribute);
    }
}
