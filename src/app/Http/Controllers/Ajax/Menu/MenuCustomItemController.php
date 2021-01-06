<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Menu;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Repositories\Menu\MenuCustomItemRepository;
use Illuminate\Http\Request;
use Webid\Cms\App\Http\Resources\Menu\MenuCustomItemResource;

class MenuCustomItemController extends BaseController
{
    /** @var MenuCustomItemRepository  */
    protected $menuCustomItemRepository;

    /**
     * @param MenuCustomItemRepository $menuCustomItemRepository
     */
    public function __construct(MenuCustomItemRepository $menuCustomItemRepository)
    {
        $this->menuCustomItemRepository = $menuCustomItemRepository;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(MenuCustomItemResource::collection($this->menuCustomItemRepository->all()));
    }
}
