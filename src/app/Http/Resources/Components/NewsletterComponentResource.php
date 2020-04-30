<?php

namespace Webid\Cms\Src\App\Http\Resources\Components;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterComponentResource extends JsonResource
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
            'name' => $this->name,
            'title' => $this->title,
            'placeholder' => $this->placeholder,
            'cta_name' => $this->cta_name,
            'view' => config("components.$this->component_type.view"),
        ];
    }
}
