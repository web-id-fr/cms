<?php

namespace Webid\Cms\Modules\Articles\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Modules\Articles\Models\Article;

class ArticleResource extends JsonResource
{
    /** @var Article */
    public $resource;

    public function toArray($request)
    {
        return [
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'image' => isset($this->resource->article_image)
                ? filemanager_full_url($this->resource->article_image)
                : null,
            'status' => Article::statusLabels()[$this->resource->status],
            'extrait' => $this->resource->extrait,
            'content' => $this->resource->content,
            'metatitle' => $this->resource->metatitle,
            'metadescription' => $this->resource->metadescription,
            'og_title' => $this->resource->opengraph_title,
            'og_description' => $this->resource->opengraph_description,
            'og_picture' => isset($this->resource->opengraph_picture)
                ? filemanager_full_url($this->resource->opengraph_picture)
                : null,
            'publish_at' => $this->resource->publish_at,
            'tags' => ArticleTagResource::collection($this->whenLoaded('tags')),
        ];
    }
}