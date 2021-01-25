<?php

namespace Webid\Cms\Modules\Galleries\Http\Services;

use Illuminate\Support\Facades\File;
use Webid\Cms\Modules\Galleries\Http\Services\Contracts\GalleryServiceContract;

class GalleryLocalStorageService implements GalleryServiceContract
{
    /**
     * @param string $folder
     *
     * @return array
     */
    public function getGalleries(string $folder = ''): array
    {
        if (!empty($folder)) {
            $galleriesPath = config('galleries.gallery_path') . '/' . $folder;
        } else {
            $galleriesPath = config('galleries.gallery_path');
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
