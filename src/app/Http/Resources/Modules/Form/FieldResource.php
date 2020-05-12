<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Form;

use Webid\Cms\Src\App\Models\Modules\Form\TitleField;
use Webid\Cms\Src\App\Models\Modules\Form\Field as FieldModel;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
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
        $field_type = config("fields_type.$this->field_type");

        return [
            $this->mergeWhen(FieldModel::class == $this->formable_type, [
                'field_options' => $this->field_options,
                'field_name' => $this->field_name,
                'field_type' => $field_type,
                'placeholder' => $this->placeholder,
                'required' => $this->required,
            ]),
            $this->mergeWhen(TitleField::class == $this->formable_type, [
                'title' => $this->title,
                'field_type' => 'legende'
            ]),
        ];
    }
}
