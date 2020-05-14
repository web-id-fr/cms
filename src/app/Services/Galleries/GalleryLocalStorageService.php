<?php

namespace Webid\Cms\Src\App\Services\Galleries;

use Illuminate\Support\Facades\File;
use Webid\Cms\Src\App\Services\Galleries\Contracts\GalleryServiceContract;

class GalleryLocalStorageService implements GalleryServiceContract
{
    /**
     * @param null $folder
     *
     * @return array
     */
    public function getGalleries($folder = null): array
    {
        if (!empty($folder)) {
            $galleriesPath = config('cms.gallery_path') . '/' . $folder;
        } else {
            $galleriesPath = config('cms.gallery_path');
        }

        $filesExist = File::exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = scandir($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
