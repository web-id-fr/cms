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
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'opengraph_title' => $this->opengraph_title,
            'opengraph_description' => $this->opengraph_description,
            'opengraph_picture' => $this->opengraph_picture,
            'indexation' => $this->getIndexationAndFollowValue($this->indexation, $this->follow),
            'meta_keywords' => $this->meta_keywords,
            'items' => ComponentResource::collection($this->component_items)->resolve(),
        ];
    }

    /**
     * @param Boolean $indexation
     * @param Boolean $follow
     *
     * @return string
     */
    public function getIndexationAndFollowValue(Boolean $indexation, Boolean $follow)
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
