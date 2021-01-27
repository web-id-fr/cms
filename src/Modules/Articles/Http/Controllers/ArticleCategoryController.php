<?php

namespace Webid\Cms\Modules\Articles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Articles\Http\Resources\ArticleResource;
use Webid\Cms\Modules\Articles\Repositories\ArticleCategoryRepository;

class ArticleCategoryController extends BaseController
{
    /** @var ArticleCategoryRepository */
    private $repository;

    public function __construct(ArticleCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        $lang = app()->getLocale();
        $category = $this->repository->getCategoryByName($request->category, $lang);

        return View::make('articles::article-category.show', [
            'category' => $request->category,
            'articles' => $this->resourceToArray(
                ArticleResource::collection($category->publishedArticlesForLang($lang))
            ),
            'current_lang' => $lang
        ]);
    }
}
