<?php

namespace Webid\Cms\Src\App\Http\Resources\Components;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Src\App\Http\Resources\Modules\Galleries\GalleryResource;
use Webid\Cms\Src\App\Models\Components\GalleryComponent;

class GalleryComponentResource extends JsonResource
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
            'galleries' => GalleryResource::collection($this->galleries)->resolve(),
            'view' => config("components." . GalleryComponent::class  .".view"),
        ];
    }
}
