<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Form;

use Illuminate\Http\Resources\Json\JsonResource;

class Service extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'recipients' => Recipient::collection($this->recipients)->resolve(),
        ];
    }
}
