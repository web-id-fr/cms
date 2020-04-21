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
            'indexation' => $this->indexation,
            'meta_keywords' => $this->meta_keywords,
            'items' => ComponentResource::collection($this->components_item)->resolve(),
        ];
    }

    /**
     * @param Boolean $indexation
     *
     * @return string
     */
    public function indexation(Boolean $indexation)
    {
        if ($indexation) {
            return 'index';
        } else {
            return 'noindex';
        }
    }
}
