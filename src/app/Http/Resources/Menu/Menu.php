<?php

namespace Webid\Cms\Src\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
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
            'zones' => $this->zones,
            'items' => MenuItem::collection($this->menu_items),
        ];
    }
}
