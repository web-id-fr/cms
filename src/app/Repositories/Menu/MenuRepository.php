<?php

namespace Webid\Cms\Src\App\Repositories\Menu;

use Webid\Cms\Src\App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Webid\Cms\Src\App\Models\Menu\Menu;

class MenuRepository extends BaseRepository
{
    /**
     * MenuRepository constructor.
     *
     * @param Menu $model
     */
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all()
    {
        $models = $this->model
            ->withZones()
            ->get();

        $models->each(function ($model) {
            $model->chargeMenuItems();
        });

        return $models;
    }

    /**
     * Retourne le Menu par l'id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        $model = $this->model->find($id);
        $model->chargeMenuItems();

        return $model;
    }

    /**
     * Attache un Menu à une zone
     *
     * @param int    $menuID
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
     * Détache le Menu d'une zone
     *
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
     * Vérivie que le Menu d'une zone existe
     *
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
