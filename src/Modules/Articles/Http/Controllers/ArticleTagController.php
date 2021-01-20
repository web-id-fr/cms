<?php

namespace Webid\Cms\Modules\Articles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Articles\Http\Resources\ArticleResource;
use Webid\Cms\Modules\Articles\Repositories\ArticleTagRepository;

class ArticleTagController extends BaseController
{
    /** @var ArticleTagRepository */
    private $repository;

    public function __construct(ArticleTagRepository $repository)
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
        $tag = $this->repository->getTagByName($request->tag, $lang);

        return View::make('articles::article-tag.show', [
            'tag' => $request->tag,
            'articles' => $this->resourceToArray(
                ArticleResource::collection($tag->publishedArticlesForLang($lang))
            ),
        ]);
    }
}
