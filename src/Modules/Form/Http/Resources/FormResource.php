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
        $this->chargeFieldItems();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'title_service' => $this->title_service,
            'cta_name' => $this->cta_name,
            'rgpd_mention' => $this->rgpd_mention,
            'confirmation_email_name' => $this->confirmation_email_name,
            'fields' => FieldResource::collection($this->field_items)->resolve(),
            'recipients' => RecipientResource::collection($this->recipients)->resolve(),
            'services' => ServiceResource::collection($this->services)->resolve(),
        ];
    }
}
