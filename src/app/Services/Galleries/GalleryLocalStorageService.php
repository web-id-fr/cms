<?php

namespace Webid\Cms\App\Services\Galleries;

use Illuminate\Support\Facades\File;
use Webid\Cms\App\Services\Galleries\Contracts\GalleryServiceContract;

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
