<?php

namespace Webid\Cms\Modules\JavaScript\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CodeSnippetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'source_code' => $this->resource->source_code,
        ];
    }
}
