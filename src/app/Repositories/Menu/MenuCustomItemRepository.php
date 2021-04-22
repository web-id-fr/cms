<?php

namespace Webid\Cms\App\Repositories\Menu;

use Webid\Cms\App\Models\Menu\MenuCustomItem;

class MenuCustomItemRepository
{
    /** @var MenuCustomItem  */
    protected $model;

    /**
     * MenuCustomItem constructor
     *
     * @param MenuCustomItem $model
     */
    public function __construct(MenuCustomItem $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all()
    {
        return $this->model
            ->with(['form', 'children'])
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|MenuCustomItem[]
     */
    public function allWithoutChildren()
    {
        return $this->model
            ->all();
    }
}
