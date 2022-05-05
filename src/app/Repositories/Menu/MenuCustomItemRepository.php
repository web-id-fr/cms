<?php

namespace Webid\Cms\App\Repositories\Menu;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Menu\MenuCustomItem;

class MenuCustomItemRepository
{
    public function __construct(private MenuCustomItem $model)
    {
    }

    public function all(): Collection
    {
        return $this->model
            ->with(['form', 'children'])
            ->get();
    }

    public function allWithoutChildren(): Collection
    {
        return $this->model
            ->all();
    }
}
