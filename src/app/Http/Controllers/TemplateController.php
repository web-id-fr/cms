<?php

namespace Webid\Cms\Src\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ExtraElementsForPageService;
use Webid\Cms\Src\App\Http\Resources\Popin\PopinResource;
use Webid\Cms\Src\App\Http\Resources\TemplateResource;
use Webid\Cms\Src\App\Repositories\Popin\PopinRepository;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Webid\Cms\Src\App\Services\LanguageService;
use Webid\Cms\Src\App\Services\TemplateService;
use Webid\Cms\Src\App\Traits\CanRenderTemplates;

class TemplateController extends Controller
{
    use CanRenderTemplates;

    /** @var TemplateRepository */
    protected $templateRepository;

    /** @var PopinRepository  */
    protected $popinRepository;

    /** @var ExtraElementsForPageService */
    protected $extraElementsForPage;

    /** @var LanguageService  */
    protected $languageService;

    /** @var TemplateService */
    protected $templateService;

    /**
     * @param TemplateRepository $templateRepository
     * @param PopinRepository $popinRepository
     * @param LanguageService $languageService
     * @param TemplateService $templateService
     */
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

            try {
                $this->extraElementsForPage = app(ExtraElementsForPageService::class)->getExtraElementForPage(data_get($data, 'id'));
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
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'languages' => $this->getAvailableLanguages(),
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

            try {
                $this->extraElementsForPage = app(ExtraElementsForPageService::class)->getExtraElementForPage(data_get($data, 'id'));
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
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'languages' => $this->getAvailableLanguages(),
                'languages_urls' => $this->templateService->getUrlsForPage($request->slug),
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
