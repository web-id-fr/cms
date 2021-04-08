<?php

namespace Webid\Cms\Modules\Articles\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Modules\Articles\Models\Article;

class RelatedArticleResource extends JsonResource
{
    /** @var Article */
    public $resource;

    public function toArray($request)
    {
        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'image' => media_full_url($this->resource->article_image),
            'image_alt' => $this->resource->article_image_alt,
            'status' => Article::statusLabels()[$this->resource->status],
            'extrait' => $this->resource->extrait,
            'content' => $this->resource->content->toArray(),
            'article_type' => $this->getArticleTypeSlug(),
            'author' => $this->resource->author,
            'publish_at' => $this->resource->publish_at,
            'categories' => ArticleCategoryResource::collection($this->whenLoaded('categories'))->resolve(),
        ];
    }
}
