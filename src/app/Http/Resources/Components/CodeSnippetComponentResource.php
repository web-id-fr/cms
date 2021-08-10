<?php

namespace Webid\Cms\App\Http\Resources\Components;

use Illuminate\Http\Resources\Json\JsonResource;
use Webid\Cms\App\Models\Components\CodeSnippetComponent;
use Webid\Cms\Modules\JavaScript\Http\Resources\CodeSnippetResource;

class CodeSnippetComponentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->component->id,
            'name' => $this->resource->component->name,
            'code_snippet' => CodeSnippetResource::make($this->resource->component->codeSnippet)->resolve(),
            'view' => config("components." . CodeSnippetComponent::class  .".view"),
        ];
    }
}
