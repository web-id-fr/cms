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
                'date_field_title' => $this->date_field_title,
                'date_field_placeholder' => $this->date_field_placeholder,
                'time_field_title' => $this->time_field_title,
                'time_field_placeholder' => $this->time_field_placeholder,
                'duration_field_title' => $this->duration_field_title,
                'field_name_time' => $this->field_name_time,
                'field_name_duration' => $this->field_name_duration,
            ]),
            $this->mergeWhen(TitleField::class == $this->formable_type, [
                'title' => $this->title,
                'field_type' => 'legende'
            ]),
        ];
    }
}
