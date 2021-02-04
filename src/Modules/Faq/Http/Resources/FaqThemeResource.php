<?php

namespace Webid\Cms\Modules\Faq\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqThemeResource extends JsonResource
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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'faq' => FaqResource::collection($this->resource->faqs)->resolve(),
        ];
    }
}
