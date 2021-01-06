<?php

namespace Webid\CardActions\App\Http\Controllers;

use Illuminate\Http\Request;
use Webid\Cms\App\Classes\VarnishCustom;
use Webid\Cms\App\Http\Controllers\BaseController;

/**
 * Class CardActionsApiController
 *
 * @package Webid\CardActions\App\Http\Controllers
 */
class CardActionsApiController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function flushVarnish(Request $request)
    {
        $host = isset(parse_url(config('app.url'))['host']) ? parse_url(config('app.url'))['host'] : null;
        $varnish = new VarnishCustom();
        $varnish->flush($host);
        return response()->json([
            'success' => true,
            'message' => 'Varnish flush for : ' . $host,
        ], 200);
    }
}
