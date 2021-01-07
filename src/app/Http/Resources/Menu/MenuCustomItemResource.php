<?php

namespace Webid\Cms\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Http\Resources\Modules\Form\FormResource;
use Webid\Cms\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;

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
            'id' => $this->id,
            'title' => $this->title,

            $this->mergeWhen(MenuCustomItemModel::_LINK_URL == $this->type_link, [
                'url' => $this->url,
            ]),

            $this->mergeWhen(MenuCustomItemModel::_LINK_FORM == $this->type_link, [
                'form' => FormResource::make($this->whenLoaded('form'))->resolve(),
                'is_popin' => true,
            ]),
        ];
    }
}
