<?php

namespace Webid\Cms\App\Http\Resources\Popin;

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
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'image' => $this->image,
            'description' => $this->description,
            'button_1_title' => $this->button_1_title,
            'button_1_url' => $this->button_1_url,
            'display_second_button' => $this->display_second_button,
            'display_call_to_action' => $this->display_call_to_action,
            'button_2_title' => $this->button_2_title,
            'button_2_url' => $this->button_2_url,
            'type' => $this->type,
            'button_name' => $this->button_name,
            'delay' => $this->delay,
            'mobile_display' => $this->mobile_display,
            'max_display' => $this->max_display
        ];
    }
}
