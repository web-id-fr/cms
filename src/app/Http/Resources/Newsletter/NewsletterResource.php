<?php

namespace Webid\Cms\Src\App\Http\Resources\Newsletter;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'lang' => $this->lang,
        ];
    }
}
