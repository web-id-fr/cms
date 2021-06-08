<?php

namespace Webid\Cms\App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function Safe\preg_match;

class TranslatableUrl implements Rule
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
        $pass = true;
        foreach ($value as $val) {
            if (! preg_match('~^(#|//|https?://|mailto:|tel:)~', $val)) {
                $pass = filter_var($val, FILTER_VALIDATE_URL) !== false;
            }
        }
        return $pass;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a valid URL.';
    }
}
