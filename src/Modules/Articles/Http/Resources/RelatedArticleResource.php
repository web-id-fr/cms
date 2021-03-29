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
        $available_types = [
            Article::_TYPE_PRESS => "press",
            Article::_TYPE_NORMAL => "normal",
            Article::_TYPE_CITATION => "citation",
        ];

        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'image' => media_full_url($this->resource->article_image),
            'image_alt' => $this->resource->article_image_alt,
            'status' => Article::statusLabels()[$this->resource->status],
            'extrait' => $this->resource->extrait,
            'content' => $this->resource->content->toArray(),
            'article_type' => $available_types[$this->resource->article_type],
            'quotation' => $this->resource->quotation,
            'author' => $this->resource->author,
            'publish_at' => $this->resource->publish_at,
            'categories' => ArticleCategoryResource::collection($this->whenLoaded('categories'))->resolve(),
        ];
    }
}