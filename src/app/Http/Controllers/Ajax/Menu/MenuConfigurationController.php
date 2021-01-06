<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Menu;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Repositories\Menu\MenuRepository;
use Webid\Cms\App\Services\MenuService;
use Illuminate\Http\Request;
use Throwable;
use Webid\Cms\App\Http\Resources\Menu\MenuZoneResource;

class MenuConfigurationController extends BaseController
{
    /** @var MenuRepository  */
    protected $menuRepository;

    /**
     * @param MenuRepository $menuRepository
     */
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * Récupère et retourne la liste des Menus en base
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $menus = MenuZoneResource::collection(app(MenuService::class)->getMenusZones());

            $menus = data_get($menus, 'data', $menus);
        } catch (Throwable $exception) {
            $menus = [];
        }

        return response()->json($menus);
    }

    /**
     * Selon les paramètres reçus, détache / attache un Menu à une zone
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateZone(Request $request)
    {
        $menu_id = (int)$request->get('menu_id', 0);
        $zone_id = $request->get('zone_id', '');

        if (empty($menu_id)) {
            $success = $this->menuRepository->detachZone($zone_id);
            $message = "The menu had been successfully removed from zone $zone_id.";
        } else {
            $success = $this->menuRepository->attachZone($menu_id, $zone_id);
            $message = "The menu #$menu_id had been successfully assigned to zone $zone_id.";
        }

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ], 400);
        }
    }
}
