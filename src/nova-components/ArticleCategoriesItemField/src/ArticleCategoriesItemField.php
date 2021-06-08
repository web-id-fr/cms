<?php

namespace Webid\ArticleCategoriesItemField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\Modules\Articles\Models\Article;
use Webid\Cms\Modules\Articles\Repositories\ArticleCategoryRepository;
use function Safe\json_decode;

class ArticleCategoriesItemField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'article-categories-item-field';

    /**
     * @param string $name
     * @param string|null $attribute
     * @param callable|null $resolveCallback
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $name, ?string $attribute = null, callable $resolveCallback = null)
    {
        $articleCategoryRepository = app()->make(ArticleCategoryRepository::class);
        $allField = $articleCategoryRepository->all();

        $this->withMeta(['items' => $allField]);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     *
     * @return mixed|void
     *
     * @throws \Safe\Exceptions\JsonException
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $articleCategoryItems = $request[$requestAttribute];
        $articleCategoryItems = collect(json_decode($articleCategoryItems, true));

        $articleCategoryItemIds = [];

        $articleCategoryItems->each(function ($articleCategoryItem, $key) use (&$articleCategoryItemIds) {
            $articleCategoryItemIds[] = $articleCategoryItem['id'];
        });

        Article::saved(function ($model) use ($articleCategoryItemIds) {
            $model->categories()->sync($articleCategoryItemIds);
        });
    }

    /**
     * @param mixed $resource
     * @param null $attribute
     *
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);
        $resource->categories();

        $valueInArray = [];
        $resource->categories->each(function ($item) use (&$valueInArray) {
            $valueInArray[] = $item;
        });

        if ($valueInArray) {
            $this->value = $valueInArray;
        }
    }

    /**
     * @param bool $isSingle
     *
     * @return ArticleCategoriesItemField
     */
    public function single(bool $isSingle)
    {
        if ($isSingle) {
            return $this->withMeta(['limit' => 1]);
        }

        return $this->withMeta(['limit' => 100]);
    }
}
