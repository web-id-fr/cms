<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Slideshow;

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
            'title' => $this->title,
            'description' => $this->description,
            'cta_name' => $this->cta_name,
            'cta_url' => $this->cta_url,
            'url' => $this->url,
            'image' => $this->image,
        ];
    }
}
