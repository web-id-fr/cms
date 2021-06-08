<?php

namespace Webid\Cms\App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function Safe\preg_match;

class IsUrlPath implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     *
     * @throws \Safe\Exceptions\PcreException
     */
    public function passes($attribute, $value)
    {
        return boolval(preg_match('/^(\/|(\/[^\/]+)+\/?)$/i', $value));
    }

    /**
     * @return array|string|null
     */
    public function message()
    {
        return __('rules.is_url_path');
    }
}
