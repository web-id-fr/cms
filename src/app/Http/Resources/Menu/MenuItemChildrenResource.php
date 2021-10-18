<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use App\Models\Template;
use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Modules\Form\Http\Resources\FormResource;

class MenuItemChildrenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var MenuCustomItem|null $menuable */
        $menuable = $this->resource->menuable;

        if (is_null($menuable)) {
            return [];
        }

        $children = $menuable->childrenForMenu($this->resource->menu_id);

        $resource = [
            'id' => $this->resource->menuable->id,
            'title' => $this->resource->menuable->title,
            'description' => $this->resource->menuable->menu_description,
            'children' => MenuItemChildrenResource::collection($children)->resolve(),
        ];

        if (MenuCustomItem::class == $this->resource->menuable_type) {
            $resource = array_merge(
                $this->menuCustomItemsExclusiveFields($menuable),
                $resource
            );
        }

        if (Template::class == $this->resource->menuable_type) {
            $resource = array_merge(
                $this->templatesExclusiveFields(),
                $resource
            );
        }

        return $resource;
    }

    private function menuCustomItemsExclusiveFields(MenuCustomItem $menuCustomItem): array
    {
        return [
            $this->mergeWhen(MenuCustomItem::_LINK_FORM == $this->resource->menuable->type_link, [
                'form' => !empty($this->resource->menuable->form)
                    ? FormResource::make($this->resource->menuable->form)->resolve()
                    : [],
                'is_popin' => true,
            ]),
            $this->mergeWhen(MenuCustomItem::_LINK_URL == $this->resource->menuable->type_link, [
                'url' => "/" . app()->getLocale() . "/$menuCustomItem->url",
                'target' => $this->resource->menuable->target,
            ]),
        ];
    }

    private function templatesExclusiveFields(): array
    {
        if (Template::class == $this->resource->menuable_type) {
            $full_path = $this->resource->menuable->getFullPath(app()->getLocale());
        } else {
            $full_path = '';
        }

        return [
            'slug' => $this->resource->menuable->slug,
            'full_path' => "/$full_path",
        ];
    }
}
