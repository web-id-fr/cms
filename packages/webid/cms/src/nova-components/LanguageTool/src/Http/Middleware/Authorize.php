<?php

namespace Webid\LanguageTool\Http\Middleware;

use Webid\LanguageTool\LanguageTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(LanguageTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
