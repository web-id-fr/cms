<?php

namespace Webid\Cms\Src\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslatableUrl implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
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
