<?php

namespace Webid\Cms\App\Http\Resources\Modules\Form;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
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
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'title_service' => $this->resource->title_service,
            'cta_name' => $this->resource->cta_name,
            'rgpd_mention' => $this->resource->rgpd_mention,
            'fields' => FieldResource::collection($this->whenLoaded('related'))->resolve(),
            'recipients' => RecipientResource::collection($this->whenLoaded('recipients'))->resolve(),
            'services' => ServiceResource::collection($this->whenLoaded('services'))->resolve(),
        ];
    }
}
