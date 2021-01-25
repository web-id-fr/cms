<?php

namespace Webid\Cms\Modules\Galleries\Http\Services;

use Illuminate\Support\Facades\Storage;
use Webid\Cms\Modules\Galleries\Http\Services\Contracts\GalleryServiceContract;

class GalleryS3Service implements GalleryServiceContract
{
    /**
     * @param string $folder
     *
     * @return array
     */
    public function getGalleries(string $folder = ''): array
    {
        if (!empty($folder)) {
            return $this->getGalleriesInFolder($folder);
        }

        $galleriesPath = config('galleries.gallery_path');
        $filesExist =  Storage::disk('s3')->exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = Storage::disk('s3')->allDirectories($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }

    /**
     * @param string $folder
     *
     * @return array|\Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getGalleriesInFolder(string $folder)
    {
        $galleriesPath = $folder;

        $filesExist =  Storage::disk('s3')->exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = Storage::disk('s3')->allFiles($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
