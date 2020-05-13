<?php

namespace Webid\Cms\Src\App\Services\Galleries;

use Illuminate\Support\Facades\File;
use Webid\Cms\Src\App\Services\Contracts\GalleryServiceContract;

class GalleryLocalStorageService implements GalleryServiceContract
{
    /**
     * @return array
     */
    public function getGalleries(): array
    {
        $galleriesPath = config('cms.gallery_path');
        $filesExist = File::exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = scandir($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
