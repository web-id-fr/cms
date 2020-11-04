<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Form;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'recipients' => RecipientResource::collection($this->whenLoaded('recipients'))->resolve(),
        ];
    }
}
