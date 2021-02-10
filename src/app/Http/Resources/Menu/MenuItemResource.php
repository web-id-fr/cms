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
        if (!empty($this->form)) {
            $form = FormResource::make($this->form)->resolve();
        } else {
            $form = null;
        }

        return [
            // Champs communs à tous les types
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->resource->menuable->menu_description,

            // Champs exclusifs aux Custom items
            $this->mergeWhen(MenuCustomItem::class == $this->menuable_type, [
                $this->mergeWhen(MenuCustomItem::_LINK_FORM == $this->type_link, [
                    'form' => $form,
                    'is_popin' => true,
                ]),
                $this->mergeWhen(MenuCustomItem::_LINK_URL == $this->type_link, [
                    'url' => $this->url,
                    'target' => $this->target,
                ]),
            ]),

            // Champs exclusifs aux Pages
            $this->mergeWhen(Template::class == $this->menuable_type, [
                'slug' => $this->slug,
            ]),

            'children' => MenuItemChildrenResource::collection($this->children)->resolve(),
        ];
    }
}
