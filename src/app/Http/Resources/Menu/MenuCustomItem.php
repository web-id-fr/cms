<?php

namespace Webid\Cms\Src\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Src\App\Http\Resources\Modules\Form\Form;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem as MenuCustomItemModel;
use Webid\Cms\Src\App\Repositories\Modules\Form\FormRepository;

class MenuCustomItem extends JsonResource
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
        $formRepository = app(FormRepository::class);

        return [
            'id' => $this->id,
            'title' => $this->title,

            $this->mergeWhen(MenuCustomItemModel::_LINK_URL == $this->type_link, [
                'url' => $this->url,
            ]),

            $this->mergeWhen(MenuCustomItemModel::_LINK_FORM == $this->type_link, [
                'form' => Form::make($formRepository->find($this->form->id))->resolve(),
                'is_popin' => true,
            ]),
        ];
    }
}
