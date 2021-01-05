<?php

namespace Webid\Cms\Modules\Redirections301\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Webid\Cms\Modules\Redirections301\Repositories\RedirectionRepository;

class RedirectOldLinks
{
    /** @var RedirectionRepository */
    private $repository;

    public function __construct(RedirectionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        $redirection = $this->repository->findBySourcePath($request->path());

        if (!is_null($redirection)) {
            return Redirect::to($redirection->destination_url, 301);
        }

        return $next($request);
    }
}
