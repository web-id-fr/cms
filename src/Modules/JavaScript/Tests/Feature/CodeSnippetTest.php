<?php

namespace Webid\Cms\Modules\JavaScript\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\JavaScript\Tests\CodeSnippetTestCase;
use Webid\Cms\Modules\JavaScript\Tests\Helpers\CodeSnippetCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class CodeSnippetTest extends CodeSnippetTestCase
{
    use CodeSnippetCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'CodeSnippets';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createCodeSnippet();
    }
}
