<?php

namespace Webid\Cms\Src\App\Services\Galleries\Contracts;

interface GalleryServiceContract
{
    /**
     * Retourne la liste des dossiers des galeries
     *
     * @return array
     */
    public function getGalleries(): array;
}
