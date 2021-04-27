<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuZoneResource extends JsonResource
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
            'menuID' => $this->resource->menuID,
            'label' => $this->resource->label,
        ];
    }
}
