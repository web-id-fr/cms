<?php

namespace Webid\Cms\App\Http\Resources\Components;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'component' => config("components.{$this->resource->component_type}.resource")::make($this)->resolve()
        ];
    }
}
