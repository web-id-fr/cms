<?php

namespace Webid\Cms\Src\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuZone extends JsonResource
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
            'menuID' => $this->menuID,
            'label' => $this->label,
        ];
    }
}
