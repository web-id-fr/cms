<?php

namespace Webid\Cms\App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     * @param array $data
     * @param bool $isSuccess
     * @param int $status
     *
     * @return JsonResponse
     */
    protected function jsonSuccess(
        string $message = "Success",
        array $data = [],
        bool $isSuccess = true,
        int $status = 200
    ) {
        return response()->json([
            'success' => $isSuccess,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    /**
     * @param JsonResource $resource
     *
     * @return array
     */
    protected function resourceToArray(JsonResource $resource): array
    {
        return $resource->response()->getData(true);
    }
}
