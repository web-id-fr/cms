<?php

namespace  Webid\Cms\Src\App\Http\Resources\Components;

use Illuminate\Http\Resources\Json\JsonResource;

class ComponentResource extends JsonResource
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
            'component' => config("components.$this->component_type.resource")::make($this)->resolve()
        ];
    }
}
