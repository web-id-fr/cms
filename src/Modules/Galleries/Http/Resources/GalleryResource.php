<?php

namespace Webid\Cms\Modules\Galleries\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\Modules\Galleries\Http\Services\Contracts\GalleryServiceContract;

class GalleryResource extends JsonResource
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
        $galleries = app(GalleryServiceContract::class)->getGalleries($this->resource->folder);

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'folder' => $this->resource->folder . '/',
            'galleries' => $galleries,
            'cta_name' => $this->resource->cta_name,
        ];
    }
}
