<?php

namespace Webid\Cms\Src\App\Services\Galleries\Contracts;

interface GalleryServiceContract
{
    /**
     * Retourne la liste des dossiers des galeries
     *
     * @param string $folder    Permet de récupèrer les galleries d'un dossier spécifique
     *
     * @return array
     */
    public function getGalleries(string $folder = ''): array;
}
