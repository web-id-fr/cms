<?php

namespace Webid\Cms\Modules\Form\Http\Resources;

use Webid\Cms\Modules\Form\Models\Field;
use Webid\Cms\Modules\Form\Models\TitleField;
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
        $field_type = config("fields_type." . $this->formable->field_type);

        return [
            $this->mergeWhen(Field::class == $this->formable_type, [
                'field_options' => !empty($this->resource->formable->field_options)
                    ? $this->resource->formable->field_options->toArray()
                    : [],
                'field_name' => $this->resource->formable->field_name ?? '',
                'field_type' => $field_type,
                'placeholder' => $this->resource->formable->placeholder ?? '',
                'required' => $this->resource->formable->required ?? '',
                'date_field_title' => $this->resource->formable->date_field_title ?? '',
                'date_field_placeholder' => $this->resource->formable->date_field_placeholder ?? '',
                'time_field_title' => $this->resource->formable->time_field_title ?? '',
                'time_field_placeholder' => $this->resource->formable->time_field_placeholder ?? '',
                'duration_field_title' => $this->resource->formable->duration_field_title ?? '',
                'field_name_time' => $this->resource->formable->field_name_time ?? '',
                'field_name_duration' => $this->resource->formable->field_name_duration ?? '',
            ]),
            $this->mergeWhen(TitleField::class == $this->formable_type, [
                'title' => $this->resource->formable->title ?? '',
                'field_type' => 'legende'
            ]),
        ];
    }
}
