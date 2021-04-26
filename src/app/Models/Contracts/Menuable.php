<?php

namespace Webid\Cms\App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

interface Menuable
{
    /**
     * Définit la relation entre le model et les menus
     *
     * @return MorphToMany
     */
    public function menus(): MorphToMany;

    /**
     * Définit la relation entre le model et les sous-menus
     *
     * @return HasMany
     */
    public function children(): HasMany;

    /**
     * Retourne la liste des sous-menus pour un menu donné
     *
     * @param int $menu_id
     * @return Collection
     */
    public function childrenForMenu(int $menu_id): Collection;
}
