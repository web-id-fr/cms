<?php

namespace Webid\Cms\Src\App\Services\Galleries;

use Illuminate\Support\Facades\Storage;
use Webid\Cms\Src\App\Services\Contracts\GalleryServiceContract;

class GalleryS3Service implements GalleryServiceContract
{
    /**
     * @return array
     */
    public function getGalleries(): array
    {
        $galleriesPath = config('cms.gallery_path');
        $filesExist = Storage::disk('s3')->exists($galleriesPath);

        if (!$filesExist) {
            return [];
        }

        $galleries = Storage::disk('s3')->allDirectories($galleriesPath);
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
