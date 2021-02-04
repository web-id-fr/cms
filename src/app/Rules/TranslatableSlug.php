<?php

namespace Webid\Cms\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslatableSlug implements Rule
{
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
        return __('validation.alpha_dash');
    }
}
