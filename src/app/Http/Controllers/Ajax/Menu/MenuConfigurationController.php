<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Menu;

use Illuminate\Http\Request;
use Throwable;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Http\Resources\Menu\MenuZoneResource;
use Webid\Cms\App\Repositories\Menu\MenuRepository;
use Webid\Cms\App\Services\MenuService;

class MenuConfigurationController extends BaseController
{
    /** @var MenuRepository */
    protected $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $menus = MenuZoneResource::collection(MenuService::make()->getMenusZones());

            $menus = data_get($menus, 'data', $menus);
        } catch (Throwable $exception) {
            $menus = [];
        }

        return response()->json($menus);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateZone(Request $request)
    {
        $menu_id = (int)$request->get('menu_id', 0);
        $zone_id = (string)$request->get('zone_id', '');

        if (empty($menu_id)) {
            $success = $this->menuRepository->detachAllMenusFromZone($zone_id);
            $message = "The menus attached to zone $zone_id had been successfully removed.";
        } else {
            $success = $this->menuRepository->attachMenuToZone($menu_id, $zone_id);
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
