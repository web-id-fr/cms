<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Form;

use Illuminate\Http\Resources\Json\JsonResource;

class Recipient extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
