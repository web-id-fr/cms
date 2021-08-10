<?php

namespace Webid\Cms\App\Repositories\Components;

use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Components\CodeSnippetComponent;

class CodeSnippetComponentRepository
{
    /** @var CodeSnippetComponent */
    protected $model;

    /**
     * @param CodeSnippetComponent $model
     */
    public function __construct(CodeSnippetComponent $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<CodeSnippetComponent>
     */
    public function getPublishedComponents()
    {
        return $this->model->all()
            ->where('status', CodeSnippetComponent::_STATUS_PUBLISHED);
    }
}
