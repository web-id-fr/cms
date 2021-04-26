<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use App\Models\Template;
use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Models\Menu\MenuItem;
use Webid\Cms\Modules\Form\Http\Resources\FormResource;

class MenuItemResource extends JsonResource
{
    /** @var MenuItem */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $menuable = $this->resource->menuable;
        $children = $menuable->childrenForMenu($this->resource->menu_id);

        return [
            // Champs communs à tous les types
            'id' => $menuable->id,
            'title' => $menuable->title,
            'children' => MenuItemChildrenResource::collection($children)->resolve(),

            // Champs exclusifs aux Custom items
            $this->mergeWhen(MenuCustomItem::class == $this->resource->menuable_type, [
                $this->mergeWhen(MenuCustomItem::_LINK_FORM == $menuable->type_link, [
                    'form' => !empty($menuable->form)
                        ? FormResource::make($menuable->form)->resolve()
                        : [],
                    'is_popin' => true,
                ]),
                $this->mergeWhen(MenuCustomItem::_LINK_URL == $menuable->type_link, [
                    'url' => $menuable->url,
                    'target' => $menuable->target,
                ]),
            ]),

            // Champs exclusifs aux Pages
            $this->mergeWhen(Template::class == $this->resource->menuable_type, [
                'slug' => $menuable->slug,
            ]),
        ];
    }
}
