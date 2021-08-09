<?php

namespace Webid\Cms\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentPageResource extends JsonResource
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
        ];
    }
}
