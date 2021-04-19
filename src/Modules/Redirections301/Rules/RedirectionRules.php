<?php

namespace Webid\Cms\Modules\Redirections301\Rules;

use Illuminate\Validation\Rule;
use Webid\Cms\App\Rules\IsUrlPath;
use Webid\Cms\Modules\Redirections301\Models\Redirection;

class RedirectionRules
{
    /**
     * @param int|null $modelIdToIgnore
     * @return array
     */
    public static function sourceUrlRules(int $modelIdToIgnore = null): array
    {
        return [
            'required',
            Rule::unique((new Redirection)->getTable(), 'source_url')->ignore($modelIdToIgnore),
            new IsUrlPath,
        ];
    }

    /**
     * @return array
     */
    public static function destinationUrlRules(): array
    {
        return [
            'required',
            new IsUrlPath,
        ];
    }
}
