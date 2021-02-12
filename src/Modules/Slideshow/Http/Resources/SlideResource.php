<?php

namespace Webid\Cms\Modules\Slideshow\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'cta_name' => $this->resource->cta_name,
            'cta_url' => $this->resource->cta_url,
            'url' => $this->resource->url,
            'image' => isset($this->resource->image)
                ? media_full_url($this->resource->image)
                : null,
            'image_alt' => $this->resource->image_alt,
        ];
    }
}
