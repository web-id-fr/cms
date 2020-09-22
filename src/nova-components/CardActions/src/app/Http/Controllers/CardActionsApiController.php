<?php

namespace Webid\CardActions\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webid\Cms\Src\App\Classes\VarnishCustom;

/**
 * Class CardActionsApiController
 *
 * @package Webid\CardActions\App\Http\Controllers
 */
class CardActionsApiController extends Controller
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
