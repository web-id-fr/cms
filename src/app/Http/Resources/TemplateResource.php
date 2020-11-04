<?php

namespace Webid\Cms\Src\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use Webid\Cms\Src\App\Http\Resources\Components\ComponentResource;

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
            'opengraph_picture' => $this->resource->opengraph_picture,
            'indexation' => $this->getIndexationAndFollowValue($this->resource->indexation, $this->resource->follow),
            'meta_keywords' => $this->resource->meta_keywords,
            'items' => ComponentResource::collection($this->resource->related)->resolve(),
        ];
    }

    /**
     * @param $indexation
     * @param $follow
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

        if($follow){
            $followValue = 'follow';
        } else {
            $followValue = 'nofollow';
        }

        return "$indexationValue,$followValue";
    }
}
