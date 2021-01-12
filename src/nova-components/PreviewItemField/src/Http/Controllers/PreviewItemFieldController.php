<?php

namespace Webid\PreviewItemField\Http\Controllers;

use Laravel\Nova\Http\Requests\NovaRequest;
use Webid\Cms\App\Http\Controllers\BaseController;

class PreviewItemFieldController extends BaseController
{
    /**
     * @param NovaRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTemplateData(NovaRequest $request)
    {
        $data = $request->all();
        $token = uniqid();

        session([$token => $data]);

        return response()->json([
            'token' => $token
        ]);
    }
}
