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
        $host = isset(parse_url(env('APP_URL'))['host']) ? parse_url(env('APP_URL'))['host'] : null;
        $varnish = new VarnishCustom();
        $varnish->flush($host);
        return response()->json([
            'success' => true,
            'message' => 'Varnish flush for : ' . $host,
        ], 200);
    }

    // Function to check string starting
    // with given substring
    protected function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
