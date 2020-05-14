<?php

namespace Webid\Cms\Src\App\Services\Galleries\Contracts;

interface GalleryServiceContract
{
    /**
     * Retourne la liste des dossiers des galeries
     *
     * @param null $folder
     *
     * @return array
     */
    public function getGalleries($folder = null): array;
}
