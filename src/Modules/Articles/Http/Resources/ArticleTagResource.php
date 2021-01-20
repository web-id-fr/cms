<?php

namespace Webid\Cms\Modules\Articles\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Modules\Articles\Models\ArticleTag;

class ArticleTagResource extends JsonResource
{
    /** @var ArticleTag */
    public $resource;

    public function toArray($request)
    {
        return [
            'name' => $this->resource->name,
        ];
    }
}
