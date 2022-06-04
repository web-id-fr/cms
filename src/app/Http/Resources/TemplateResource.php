<?php

namespace Webid\Cms\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Webid\Cms\App\Http\Resources\Components\ComponentResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'slug' => $this->resource->slug,
            'status' => $this->resource->status,
            'meta_title' => $this->resource->metatitle,
            'meta_description' => $this->resource->metadescription,
            'opengraph_title' => $this->resource->opengraph_title,
            'opengraph_description' => $this->resource->opengraph_description,
            'opengraph_picture' => media_full_url($this->resource->opengraph_picture),
            'opengraph_picture_alt' => $this->resource->opengraph_picture_alt,
            'indexation' => $this->getIndexationAndFollowValue($this->resource->indexation, $this->resource->follow),
            'meta_keywords' => $this->resource->meta_keywords,
            'items' => ComponentResource::collection($this->resource->related)->resolve(),
            'menu_description' => $this->resource->menu_description,
            'breadcrumb' => $this->resource->getBreadcrumb(App::getLocale()),
        ];
    }

    /**
     * @param int|bool $indexation
     * @param int|bool $follow
     *
     * @return string
     */
    public function getIndexationAndFollowValue($indexation, $follow)
    {
        if ($indexation) {
            $indexationValue = 'index';
        } else {
            $indexationValue = 'noindex';
        }

        if ($follow) {
            $followValue = 'follow';
        } else {
            $followValue = 'nofollow';
        }

        return "$indexationValue,$followValue";
    }
}
