<?php

namespace Webid\Cms\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
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
            'id' => $this->id,
            'title' => $this->title,
            'menu_description' => $this->resource->menu_description,
            'slug' => $this->slug,
            'status' => $this->status,
            'meta_title' => $this->metatitle,
            'meta_description' => $this->metadescription,
            'opengraph_title' => $this->opengraph_title,
            'opengraph_description' => $this->opengraph_description,
            'opengraph_picture' => $this->opengraph_picture,
            'indexation' => $this->getIndexationAndFollowValue($this->indexation, $this->follow),
            'meta_keywords' => $this->meta_keywords,
            'items' => ComponentResource::collection($this->component_items)->resolve(),
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

        if ($follow) {
            $followValue = 'follow';
        } else {
            $followValue = 'nofollow';
        }

        return "$indexationValue,$followValue";
    }
}
