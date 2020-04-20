<?php

namespace Webid\Cms\Src\App\Repositories\Menu;

use Webid\Cms\Src\App\Models\Menu\MenuCustomItem;
use Webid\Cms\Src\App\Repositories\BaseRepository;

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
     * @param bool $paginate
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all(Bool $paginate = false, Array $options = [])
    {
        //OPTIONS
        $search = isset($options['search']) ? $options['search'] : null;
        $notIn = isset($options['notIn']) ? $options['notIn'] : null;
        $in = isset($options['in']) ? $options['in'] : null;

        $query = $this->model;
        if ($search) {
            $query = $query->where('title', 'LIKE', "%$search%");
        }
        if ($notIn) {
            $query = $query->whereNotIn('id', $notIn);
        }
        if ($in) {
            $query = $query->whereIn('id', $in);
        }
        $query = $query->orderBy('updated_at', 'desc');

        return $paginate ? $query->paginate(env('MODULE_MENU_PAGINATE', 15)) : $query->get();
    }
}
