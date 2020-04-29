<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Form;

use Illuminate\Http\Resources\Json\JsonResource;

class Form extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'title_service' => $this->title_service,
            'cta_name' => $this->cta_name,
            'rgpd_mention' => $this->rgpd_mention,
            'fields' => Field::collection($this->field_items)->resolve(),
            'recipients' => Recipient::collection($this->recipients)->resolve(),
            'services' => Service::collection($this->services)->resolve(),
        ];
    }
}
