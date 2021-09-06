<?php

namespace Webid\Cms\App\Http\Resources\Components;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Components\NewsletterComponent;

class NewsletterComponentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->mergeWhen($this->resource->component->status === NewsletterComponent::_STATUS_PUBLISHED, [
                'id' => $this->resource->component->id,
                'name' => $this->resource->component->name,
                'title' => $this->resource->component->title,
                'placeholder' => $this->resource->component->placeholder,
                'cta_name' => $this->resource->component->cta_name,
                'view' => config("components." . NewsletterComponent::class . ".view"),
            ]),
        ];
    }
}
