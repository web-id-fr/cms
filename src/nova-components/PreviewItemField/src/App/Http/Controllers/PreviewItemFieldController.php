<?php

namespace Webid\PreviewItemField\App\Http\Controllers;

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
        try {
            $data = $request->all();
            $token = uniqid();

            session([$token => $data]);

            return response()->json([
                'token' => $token
            ]);
        } catch (\Exception $exception) {
            abort(404);
        }
    }
}
