<?php

namespace Webid\Cms\App\Http\Resources\Modules\Galleries;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Services\Galleries\Contracts\GalleryServiceContract;

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
        $galleries = app(GalleryServiceContract::class)->getGalleries($this->folder);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'folder' => $this->folder . '/',
            'galleries' => $galleries,
            'cta_name' => $this->cta_name,
        ];
    }
}
