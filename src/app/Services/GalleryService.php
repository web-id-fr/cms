<?php

namespace Webid\Cms\Src\App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GalleryService
{
    /**
     * @return array
     */
    public function getGalleries(): array
    {
        $galleriesPath = config('cms.gallery_path');
        $files = 's3' == config('cms.filesystem_driver') ? Storage::disk('s3')->exists($galleriesPath) : File::exists($galleriesPath);
        $directories = 's3' == config('cms.filesystem_driver') ? Storage::disk('s3')->allDirectories($galleriesPath) : scandir($galleriesPath);

        if (!$files) {
            return [];
        }

        $galleries = $directories;
        $galleries = array_diff($galleries, ['.', '..']);

        return $galleries;
    }
}
