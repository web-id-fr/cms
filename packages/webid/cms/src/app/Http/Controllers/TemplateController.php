<?php

namespace Webid\Cms\Src\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Facades\LanguageFacade;
use Webid\Cms\Src\App\Http\Resources\TemplateResource;
use Webid\Cms\Src\App\Repositories\TemplateRepository;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /** @var $templateRepository */
    protected $templateRepository;

    /**
     * @param TemplateRepository $templateRepository
     */
    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
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

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'author' => data_get($data, 'meta_author'),
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'follow' => data_get($data, 'follow'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'currentLang' => request()->lang ?? '',
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

            $meta = [
                'title' => data_get($data, 'meta_title'),
                'type' => 'realisations',
                'author' => data_get($data, 'meta_author'),
                'description' => data_get($data, 'meta_description'),
                'og_title' => data_get($data, 'opengraph_title'),
                'og_image' => data_get($data, 'opengraph_picture'),
                'og_description' => data_get($data, 'opengraph_description'),
                'follow' => data_get($data, 'follow'),
                'indexation' => data_get($data, 'indexation'),
                'keywords' => data_get($data, 'meta_keywords'),
            ];

            return view('template', [
                'data' => $data,
                'meta' => $meta,
                'currentLang' => request()->lang ?? '',
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
