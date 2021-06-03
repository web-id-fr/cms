<?php

namespace Webid\Cms\App\Repositories\Menu;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webid\Cms\App\Models\Menu\Menu;

class MenuRepository
{
    protected Menu $model;

    /**
     * @param Menu $model
     */
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<Menu>
     */
    public function all()
    {
        return $this->model
            ->withZones()
            ->with(['items' => function ($query) {
                $query->with('menus.children')
                    ->whereHas('menus', function ($query) {
                        $query->whereNull('parent_id');
                    });
            }])
            ->get();
    }

    /**
     * @param int $id
     *
     * @return Menu|null
     */
    public function find(int $id)
    {
        /** @var Menu|null $menu */
        $menu = $this->model
            ->find($id);

        if ($menu) {
             $menu->with(['items' => function ($query) {
                $query->whereHas('menus', function ($query) {
                    $query->whereNull('parent_id');
                });
            }]);
        }

        return $menu;
    }

    /**
     * @param int $menuID
     * @param string $zoneID
     *
     * @return bool
     */
    public function attachZone(int $menuID, string $zoneID): bool
    {
        if (empty($menuID) || empty($zoneID)) {
            return false;
        }

        return DB::table('menus_zones')
            ->updateOrInsert(
                ['zone_id' => $zoneID],
                ['menu_id' => $menuID]
            );
    }

    /**
     * @param string $zoneID
     *
     * @return bool
     */
    public function detachZone(string $zoneID): bool
    {
        if (empty($zoneID)) {
            return false;
        }

        $deletedRows = DB::table('menus_zones')
            ->where('zone_id', $zoneID)
            ->delete();

        return $deletedRows > 0;
    }

    /**
     * @param string $zoneID
     *
     * @return bool
     */
    public function menuZoneExist(string $zoneID): bool
    {
        if (empty($zoneID)) {
            return false;
        }

        return DB::table('menus_zones')
            ->where('zone_id', $zoneID)
            ->exists();
    }
}
