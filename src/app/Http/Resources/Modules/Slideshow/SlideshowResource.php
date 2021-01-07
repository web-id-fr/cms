<?php

namespace Webid\Cms\App\Http\Resources\Modules\Slideshow;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideshowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->resource->title,
            'js_controls' => $this->resource->js_controls,
            'js_animate_auto' => $this->resource->js_animate_auto,
            'js_speed' => $this->resource->js_speed,
            'slides' => SlideResource::collection($this->resource->slides)->resolve(),
        ];
    }
}
