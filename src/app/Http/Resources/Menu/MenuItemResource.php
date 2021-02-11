<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use App\Models\Template;
use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Modules\Form\Http\Resources\FormResource;

class MenuItemResource extends JsonResource
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
            'id' => $this->resource->menuable->id,
            'title' => $this->resource->menuable->title,
            'description' => $this->resource->menuable->menu_description,

            // Champs exclusifs aux Custom items
            $this->mergeWhen(MenuCustomItem::class == $this->resource->menuable_type, [
                $this->mergeWhen(MenuCustomItem::_LINK_FORM == $this->resource->menuable->type_link, [
                    'form' => !empty($this->resource->menuable->form)
                        ? FormResource::make($this->resource->menuable->form)->resolve()
                        : [],
                    'is_popin' => true,
                ]),
                $this->mergeWhen(MenuCustomItem::_LINK_URL == $this->resource->menuable->type_link, [
                    'url' => $this->resource->menuable->url,
                    'target' => $this->resource->menuable->target,
                ]),
            ]),

            // Champs exclusifs aux Pages
            $this->mergeWhen(Template::class == $this->resource->menuable_type, [
                'slug' => $this->resource->menuable->slug,
            ]),

            'children' => MenuItemChildrenResource::collection($this->resource->menuable->children)->resolve(),
        ];
    }
}
