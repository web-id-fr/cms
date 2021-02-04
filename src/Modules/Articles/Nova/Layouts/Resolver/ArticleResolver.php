<?php

namespace Webid\Cms\Modules\Articles\Nova\Layouts\Resolver;

use Illuminate\Support\Collection;
use Webid\Cms\App\Http\Resources\Modules\Slideshow\SlideshowResource;
use Webid\Cms\App\Repositories\Modules\Slideshow\SlideshowRepository;
use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class ArticleResolver implements ResolverInterface
{
    /**
     * @param $model
     * @param $attribute
     * @param $layouts
     *
     * @return mixed
     */
    public function get($model, $attribute, $layouts)
    {
        $value = $this->extractValueFromResource($model, $attribute);

        return collect($value)->map(function ($item) use ($layouts) {
            $layout = $layouts->find($item->layout);

            if (!$layout) {
                return '';
            }

            if ($item->layout === 'slideshow') {
                return $layout->duplicateAndHydrate($item->key, [
                    'slideshow_select' => $item->attributes['id']
                ]);
            } else {
                return $layout->duplicateAndHydrate($item->key, (array) $item->attributes);
            }
        })->filter()->values();
    }

    /**
     * @param $model
     * @param $attribute
     * @param $value
     *
     * @return mixed
     */
    public function set($model, $attribute, $value)
    {
        $class = get_class($model);

        return $model->$attribute = $value->map(function ($group) use ($class) {
            $attributes = $group->getAttributes();

            if ($group->name() === 'slideshow') {
                $slideshowId = $group->slideshow_select;

                $class::saved(function ($model) use ($group, $slideshowId) {
                    $model->slideshows()->sync($slideshowId);
                });

                $attributes = [
                    "id" => $slideshowId,
                    "slideshow_select" => SlideshowResource::make(
                        app(SlideshowRepository::class)->find($slideshowId)
                    )->resolve()
                ];
            }

            $attributes['layout'] = $group->name();

            return [
                'layout' => $group->name(),
                'key' => $group->key(),
                'attributes' => $attributes
            ];
        });
    }

    /**
     * @param $resource
     * @param $attribute
     *
     * @return array
     */
    protected function extractValueFromResource($resource, $attribute)
    {
        $value = data_get($resource, str_replace('->', '.', $attribute)) ?? [];

        if ($value instanceof Collection) {
            $value = $value->toArray();
        } elseif (is_string($value)) {
            $value = json_decode($value) ?? [];
        }

        if (!is_array($value)) {
            return [];
        }

        return array_map(function ($item) {
            return is_array($item) ? (object)$item : $item;
        }, $value);
    }
}
