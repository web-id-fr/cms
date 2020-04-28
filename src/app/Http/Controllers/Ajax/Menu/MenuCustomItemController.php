<?php

namespace Webid\Cms\Src\App\Http\Controllers\Ajax\Menu;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Repositories\Menu\MenuCustomItemRepository;
use Illuminate\Http\Request;
use Webid\Cms\Src\App\Http\Resources\Menu\MenuCustomItem as MenuCustomItemResource;

class MenuCustomItemController extends Controller
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
        return response()->json(MenuCustomItemResource::collection($this->menuCustomItemRepository->getPaginateAndFilter(!!$request->page, [
            'search' => $request->search,
            'notIn' => $request->notIn,
            'in' => $request->in
        ])));
    }
}
