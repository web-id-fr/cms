<?php

namespace Webid\Cms\Src\App\Http\Controllers\Ajax\Menu;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Http\Resources\Menu\Menu as MenuResource;
use Webid\Cms\Src\App\Repositories\Menu\MenuRepository;

class MenuController extends Controller
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return MenuResource::collection($this->menuRepository->all());
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json(MenuResource::make($this->menuRepository->find($id)));
    }
}
