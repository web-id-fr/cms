<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;
use Webid\Cms\Modules\Form\Http\Resources\FormResource;

class MenuCustomItemResource extends JsonResource
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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'menu_description' => $this->resource->menu_description,

            $this->mergeWhen(MenuCustomItemModel::_LINK_URL == $this->resource->type_link, [
                'url' => $this->resource->url,
            ]),

            $this->mergeWhen(MenuCustomItemModel::_LINK_FORM == $this->resource->type_link, [
                'form' => FormResource::make($this->whenLoaded('form'))->resolve(),
                'is_popin' => true,
            ]),
        ];
    }
}
