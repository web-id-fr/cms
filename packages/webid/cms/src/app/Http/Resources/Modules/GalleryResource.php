<?php

namespace Webid\Cms\Src\App\Http\Resources\Modules\Galleries;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\File;

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
        $filepath = storage_path('app/public/galleries/' . $this->folder);

        if (File::exists($filepath)) {
            $galleries = scandir($filepath);
            $galleries = array_diff($galleries, ['.', '..']);
        } else{
            $galleries = [];
        }

        return [
            'title' => $this->title,
            'folder' => $galleries,
            'cta_name' => $this->cta_name,
        ];
    }
}
