<?php

namespace Webid\Cms\Src\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslatableSlug implements Rule
{
    /**
     * TranslatableSlug constructor.
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
            if (! preg_match('/^[\pL\pM\pN_-]+$/u', $val)) {
                $pass = false;
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
        return 'The :attribute may only contain letters, numbers, dashes and underscores.';
    }
}
