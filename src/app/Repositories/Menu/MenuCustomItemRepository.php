<?php

namespace Webid\Cms\App\Repositories\Menu;

use Webid\Cms\App\Models\Menu\MenuCustomItem;
use Webid\Cms\App\Repositories\BaseRepository;

class MenuCustomItemRepository extends BaseRepository
{
    /**
     * MenuCustomItem constructor
     *
     * @param MenuCustomItem $model
     */
    public function __construct(MenuCustomItem $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all()
    {
        return $this->model
            ->with(['menus', 'form'])->get();
    }
}
