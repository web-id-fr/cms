<?php

namespace Webid\Cms\App\Http\Resources\Components;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Components\GalleryComponent;
use Webid\Cms\Modules\Galleries\Http\Resources\GalleryResource;

class GalleryComponentResource extends JsonResource
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
            $this->mergeWhen($this->resource->component->status === GalleryComponent::_STATUS_PUBLISHED, [
                'id' => $this->resource->component->id,
                'name' => $this->resource->component->name,
                'galleries' => GalleryResource::collection($this->resource->component->galleries)->resolve(),
                'view' => config("components." . GalleryComponent::class . ".view"),
            ]),
        ];
    }
}
