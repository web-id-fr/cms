<?php

namespace Webid\Cms\Modules\Articles\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Modules\Articles\Models\ArticleCategory;

class ArticleCategoryResource extends JsonResource
{
    /** @var ArticleCategory */
    public $resource;

    public function toArray($request)
    {
        return [
            'name' => $this->resource->name,
        ];
    }
}
