<?php

namespace Webid\Cms\App\Http\Controllers;

use Illuminate\Http\Request;

class PreviewController extends BaseController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function preview(Request $request)
    {
        try {
            $components = [];
            $token = $request->token;
            $data = session($token);

            foreach (data_get($data, 'components') as $component) {
                $model = app($component['component_type'])::find($component['id']);
                $resource = config('components.' . $component['component_type'] . '.resource');
                $dataResource = $resource::make($model)->resolve();
                $dataResource['view'] = config("components." . data_get($component, 'component_type') . ".view");
                $components[] = $dataResource;
            }

            $data['components'] = $components;

            return view("preview", ['data' => $data]);
        } catch (\Exception $exception) {
            abort(404);
        }
    }
}
