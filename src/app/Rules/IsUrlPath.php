<?php

namespace Webid\Cms\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsUrlPath implements Rule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return boolval(preg_match('/^(\/[^\/]+)+\/?$/i', $value));
    }

    /**
     * @return string
     */
    public function message()
    {
        return __('rules.is_url_path');
    }
}
