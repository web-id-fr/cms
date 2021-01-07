<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Menu;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Http\Resources\Menu\MenuResource;
use Webid\Cms\App\Repositories\Menu\MenuRepository;

class MenuController extends BaseController
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
