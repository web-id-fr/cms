<?php

namespace Webid\Cms\Src\App\Services\Galleries;

use Illuminate\Support\Facades\Storage;
use Webid\Cms\Src\App\Services\Galleries\Contracts\GalleryServiceContract;

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
