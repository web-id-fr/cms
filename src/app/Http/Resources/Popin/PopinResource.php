<?php

namespace Webid\Cms\Src\App\Http\Resources\Popin;

use Illuminate\Http\Resources\Json\JsonResource;

class PopinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'status' => $this->resource->status,
            'image' => $this->resource->image,
            'description' => $this->resource->description,
            'button_1_title' => $this->resource->button_1_title,
            'button_1_url' => $this->resource->button_1_url,
            'display_second_button' => $this->resource->display_second_button,
            'display_call_to_action' => $this->resource->display_call_to_action,
            'button_2_title' => $this->resource->button_2_title,
            'button_2_url' => $this->resource->button_2_url,
            'type' => $this->resource->type,
            'button_name' => $this->resource->button_name,
            'delay' => $this->resource->delay,
            'mobile_display' => $this->resource->mobile_display,
            'max_display' => $this->resource->max_display
        ];
    }
}
