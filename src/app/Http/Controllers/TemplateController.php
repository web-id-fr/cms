<?php

namespace Webid\Cms\Src\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ExtraElementForPageService;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use Webid\Cms\Src\App\Http\Resources\Popin\PopinResource;
use Webid\Cms\Src\App\Http\Resources\TemplateResource;
use Webid\Cms\Src\App\Repositories\Popin\PopinRepository;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Webid\Cms\Src\App\Traits\CanRenderTemplates;

class TemplateController extends Controller
{
    use CanRenderTemplates;

    /** @var $templateRepository */
    protected $templateRepository;

    /** @var PopinRepository  */
    protected $popinRepository;

    /** @var $extraElementForPages */
    protected $extraElementForPages;

    /**
     * @param TemplateRepository $templateRepository
     * @param PopinRepository $popinRepository
     */
    public function __construct(TemplateRepository $templateRepository, PopinRepository $popinRepository)
    {
        $this->templateRepository = $templateRepository;
        $this->popinRepository = $popinRepository;
        $this->extraElementForPages = [];
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
                $this->extraElementForPages = app(ExtraElementForPageService::class)->getExtraElementForPage(data_get($data, 'id'));
            } catch (\Exception $e) {
                info($e);
            }

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'author' => data_get($data, 'meta_author'),
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
                'currentLang' => request()->lang ?? '',
                'popins' => PopinResource::collection($popins)->resolve(),
                'extras' => $this->extraElementForPages,
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
            if ($this->templateRepository->getSlugForHomepage() == $request->slug) {
                return redirect(route('home'), 301);
            }

            $data = TemplateResource::make($this->templateRepository->getBySlug(
                $request->slug,
                app()->getLocale()
            ))->resolve();

            $popins = $this->popinRepository->findByPageId(data_get($data, 'id'));

            try {
                $this->extraElementForPages = app(ExtraElementForPageService::class)->getExtraElementForPage(data_get($data, 'id'));
            } catch (\Exception $e) {
                info($e);
            }

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'author' => data_get($data, 'meta_author'),
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
                'currentLang' => request()->lang ?? '',
                'popins' => PopinResource::collection($popins)->resolve(),
                'extras' => $this->extraElementForPages,
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
        return redirect(LanguageFacade::getFromBrowser());
    }
}
