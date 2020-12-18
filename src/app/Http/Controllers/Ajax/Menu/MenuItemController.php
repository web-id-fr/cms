<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Menu;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Repositories\Menu\MenuCustomItemRepository;
use Webid\Cms\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Webid\Cms\App\Http\Resources\Menu\MenuItemResource;

class MenuItemController extends BaseController
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
