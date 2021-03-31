<?php

namespace Webid\Cms\Modules\Articles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Articles\Http\Resources\ArticleResource;
use Webid\Cms\Modules\Articles\Repositories\ArticleRepository;

class ArticleController extends BaseController
{
    private ArticleRepository $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        $article = $this->repository->getBySlug($request->slug, app()->getLocale());

        return View::make('articles::article.show', [
            'article' => $this->resourceToArray(ArticleResource::make($article)),
        ]);
    }
}
