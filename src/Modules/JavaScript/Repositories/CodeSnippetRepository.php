<?php

namespace Webid\Cms\Modules\JavaScript\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\JavaScript\Models\CodeSnippet;

class CodeSnippetRepository
{
    /** @var CodeSnippet */
    protected $model;

    /**
     * CodeSnippetRepository constructor.
     *
     * @param CodeSnippet $model
     */
    public function __construct(CodeSnippet $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<CodeSnippet>
     */
    public function getAllCodeSnippets()
    {
        return $this->model->all();
    }
}
