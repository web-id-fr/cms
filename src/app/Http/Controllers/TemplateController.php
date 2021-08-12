<?php

namespace Webid\Cms\App\Http\Controllers;

use App\Services\ExtraElementsForPageService;
use Webid\Cms\App\Http\Resources\Popin\PopinResource;
use Webid\Cms\App\Http\Resources\TemplateResource;
use Webid\Cms\App\Repositories\Popin\PopinRepository;
use Webid\Cms\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Webid\Cms\App\Services\LanguageService;
use Webid\Cms\App\Services\TemplateService;

class TemplateController extends BaseController
{
    protected TemplateRepository $templateRepository;
    protected PopinRepository $popinRepository;
    protected LanguageService $languageService;
    protected TemplateService $templateService;
    protected array $extraElementsForPage;

    public function __construct(
        TemplateRepository $templateRepository,
        PopinRepository $popinRepository,
        LanguageService $languageService,
        TemplateService $templateService
    ) {
        $this->templateRepository = $templateRepository;
        $this->popinRepository = $popinRepository;
        $this->languageService = $languageService;
        $this->templateService = $templateService;
        $this->extraElementsForPage = [];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function index(Request $request)
    {
        try {
            $slug = $this->templateRepository->getSlugForHomepage();
            $template = $this->templateRepository->getBySlugWithRelations(
                $slug->slug,
                app()->getLocale()
            );

            $data = TemplateResource::make($template)->resolve();

            $popins = $this->popinRepository->findByPageId(data_get($data, 'id'));

            try {
                $extraElementsService = app(ExtraElementsForPageService::class);
                $this->extraElementsForPage = $extraElementsService->getExtraElementForPage(data_get($data, 'id'));
            } catch (\Exception $e) {
                info($e);
            }

            /** @var array $queryParams */
            $queryParams = $request->query();

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
                'canonical' => $this->templateService->getCanonicalUrlFor($template, $queryParams),
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'popins' => PopinResource::collection($popins)->resolve(),
                'extras' => $this->extraElementsForPage,
            ]);
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        try {
            $path = $request->path();
            $slugs = explode('/', $path);
            $slug = end($slugs);

            $template = $this->templateRepository->getBySlugWithRelations(
                $slug,
                app()->getLocale()
            );

            $data = TemplateResource::make($template)->resolve();

            $popins = $this->popinRepository->findByPageId(data_get($data, 'id'));

            /** @var array $queryParams */
            $queryParams =  $request->query();

            try {
                $extraElementsService = app(ExtraElementsForPageService::class);
                $this->extraElementsForPage = $extraElementsService->getExtraElementForPage(data_get($data, 'id'));
            } catch (\Exception $e) {
                info($e);
            }

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
                'canonical' => $this->templateService->getCanonicalUrlFor($template, $queryParams, reset($slugs)),
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'popins' => PopinResource::collection($popins)->resolve(),
                'extras' => $this->extraElementsForPage,
            ]);
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    /**
     * Affiche la page d'accueil avec la langue par défault
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function rootPage()
    {
        return redirect($this->languageService->getFromBrowser());
    }
}
