<?php

namespace Webid\Cms\Src\App\Services;

use Illuminate\Support\Facades\File;

class GalleryService
{
    /**
     * @return array
     */
    public function getGalleries(): array
    {
        $galleriesPath = storage_path('app/public/galleries/');

        if (!File::exists($galleriesPath)) {
            return [];
        }

        $galleries = scandir($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
