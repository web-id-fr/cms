<?php

namespace Webid\Cms\Modules\JavaScript\Tests\Helpers;

use Webid\Cms\Modules\JavaScript\Models\CodeSnippet;

trait CodeSnippetCreator
{
    /**
     * @param array $parameters
     *
     * @return CodeSnippet
     */
    private function createCodeSnippet(array $parameters = []): CodeSnippet
    {
        return CodeSnippet::factory($parameters)->create();
    }
}
