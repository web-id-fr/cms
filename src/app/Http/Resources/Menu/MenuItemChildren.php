<?php

namespace Webid\Cms\Src\App\Http\Resources\Menu;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Src\App\Http\Resources\Modules\Form\Form;
use Webid\Cms\Src\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Src\App\Repositories\Modules\Form\FormRepository;

class MenuItemChildren extends JsonResource
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

        if(!empty($this->form_id)){
            $form = Form::make($formRepository->find($this->form_id))->resolve();
        } else {
            $form = '';
        }

        return [
            // Champs communs à tous les types
            'id' => $this->id,
            'title' => $this->title,

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
            $this->mergeWhen(config('cms.template_model') == $this->menuable_type, [
                'slug' => $this->slug,
            ]),
        ];
    }
}
