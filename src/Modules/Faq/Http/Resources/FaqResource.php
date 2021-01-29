<?php

namespace Webid\Cms\Modules\Faq\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'question' => $this->resource->question,
            'answer' => $this->resource->answer,
            'order' => $this->resource->order,
        ];
    }
}
