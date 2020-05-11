<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Slideshow;

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
        $this->chargeSlideItems();

        return [
            'title' => $this->title,
            'js_controls' => $this->js_controls,
            'js_animate_auto' => $this->js_animate_auto,
            'js_speed' => $this->js_speed,
            'slides' => SlideResource::collection($this->slides)->resolve(),
        ];
    }
}
