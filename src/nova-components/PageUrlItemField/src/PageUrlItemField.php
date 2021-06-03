<?php

namespace Webid\PageUrlItemField;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\Field;

class PageUrlItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'PageUrlItemField';

    /**
     * @param string $name
     * @param string|callable|null $attribute
     * @param callable|null $resolveCallback
     *
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));

        $this->withMeta([
            'locales' => $locales,
            'indexLocale' => app()->getLocale()
        ]);
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param Model $resource
     * @param string $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        if (method_exists($resource, 'getTranslations')) {
            return $resource->getTranslations($attribute);
        }
        return data_get($resource, $attribute);
    }

    /**
     * Set the locales to display / edit.
     *
     * @param  array  $locales
     * @return $this
     */
    public function locales(array $locales)
    {
        return $this->withMeta([
            'locales' => $locales
        ]);
    }

    /**
     * Set the locale to display on index.
     *
     * @param  string $locale
     * @return $this
     */
    public function indexLocale($locale)
    {
        return $this->withMeta([
            'indexLocale' => $locale
        ]);
    }

    /**
     * @return PageUrlItemField
     */
    public function projectUrl(string $url)
    {
        return $this->withMeta([
            'projectUrl' => $url
        ]);
    }
}
