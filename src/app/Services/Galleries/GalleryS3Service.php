<?php

namespace Webid\Cms\Src\App\Services\Galleries;

use Illuminate\Support\Facades\Storage;
use Webid\Cms\Src\App\Services\Galleries\Contracts\GalleryServiceContract;

class GalleryS3Service implements GalleryServiceContract
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

        $filesExist = Storage::disk('s3')->exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = Storage::disk('s3')->allDirectories($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
