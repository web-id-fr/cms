<?php

namespace Webid\Cms\Src\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem;

class MenuItem extends JsonResource
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
            // Champs communs à tous les types
            'id' => $this->id,
            'title' => $this->title,

            // Champs exclusifs aux Custom items
            'url' => $this->when(MenuCustomItem::class == $this->menuable_type, function () {
                return $this->url;
            }),

            'target' => $this->when(MenuCustomItem::class == $this->menuable_type, function () {
                return $this->target;
            }),

            // Champs exclusifs aux Pages
            'slug' => $this->when(config('cms.template_model') == $this->menuable_type, function () {
                return $this->slug;
            }),

            'children' => MenuItemChildren::collection($this->children)->resolve(),
        ];
    }
}
