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
    /** @var TemplateRepository */
    protected $templateRepository;

    /** @var PopinRepository  */
    protected $popinRepository;

    /** @var LanguageService  */
    protected $languageService;

    /** @var TemplateService */
    protected $templateService;

    /** @var ExtraElementsForPageService */
    protected $extraElementsService;

    /** @var array */
    protected $extraElementsForPage;

    /**
     * @param TemplateRepository $templateRepository
     * @param PopinRepository $popinRepository
     * @param LanguageService $languageService
     * @param TemplateService $templateService
     * @param ExtraElementsForPageService $extraElementsService
     */
    public function __construct(
        TemplateRepository $templateRepository,
        PopinRepository $popinRepository,
        LanguageService $languageService,
        TemplateService $templateService,
        ExtraElementsForPageService $extraElementsService
    ) {
        $this->templateRepository = $templateRepository;
        $this->popinRepository = $popinRepository;
        $this->languageService = $languageService;
        $this->templateService = $templateService;
        $this->extraElementsService = $extraElementsService;
        $this->extraElementsForPage = [];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public function index()
    {
        try {
            $slug = $this->templateRepository->getSlugForHomepage();

            $data = TemplateResource::make($this->templateRepository->getBySlug(
                $slug->slug,
                app()->getLocale()
            ))->resolve();

            $popins = $this->popinRepository->findByPageId(data_get($data, 'id'));

            $this->extraElementsForPage = $this->extraElementsService->getExtraElementForPage(data_get($data, 'id'));

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|null
     */
    public function show(Request $request)
    {
        try {
            if ($this->templateService->getHomepageSlug() == $request->slug) {
                return redirect(route('home'), 301);
            }

            $data = TemplateResource::make($this->templateRepository->getBySlug(
                $request->slug,
                app()->getLocale()
            ))->resolve();

            $popins = $this->popinRepository->findByPageId(data_get($data, 'id'));

            $this->extraElementsForPage = $this->extraElementsService->getExtraElementForPage(data_get($data, 'id'));

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
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
