<?php

namespace Webid\Cms\Src\App\Http\Controllers\Ajax\Menu;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Repositories\Menu\MenuCustomItemRepository;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Webid\Cms\Src\App\Http\Resources\Menu\MenuItem as MenuItemResource;

class MenuItemController extends Controller
{
    /** @var MenuCustomItemRepository  */
    protected $menuCustomItemRepository;

    /** @var TemplateRepository  */
    protected $templateRepository;

    /**
     * @param MenuCustomItemRepository $menuCustomItemRepository
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        MenuCustomItemRepository $menuCustomItemRepository,
        TemplateRepository $templateRepository
    ) {
        $this->menuCustomItemRepository = $menuCustomItemRepository;
        $this->templateRepository = $templateRepository;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $allCustomItem = $this->menuCustomItemRepository->all();
        $allPage = $this->templateRepository->getPublishedTemplates();
        $allItem = $allCustomItem->merge($allPage);

        return response()->json(MenuItemResource::collection($allItem));
    }
}
