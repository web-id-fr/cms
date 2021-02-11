<?php

namespace Webid\Cms\Modules\Form\Http\Resources;

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
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'title_service' => $this->resource->title_service,
            'cta_name' => $this->resource->cta_name,
            'rgpd_mention' => $this->resource->rgpd_mention,
            'confirmation_email_name' => $this->resource->confirmation_email_name,
            'fields' => FieldResource::collection($this->whenLoaded('related'))->resolve(),
            'recipients' => RecipientResource::collection($this->whenLoaded('recipients'))->resolve(),
            'services' => ServiceResource::collection($this->whenLoaded('services'))->resolve(),
        ];
    }
}
