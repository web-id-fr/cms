<?php

namespace Webid\Cms\App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function Safe\preg_match;

class TranslatableSlug implements Rule
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
            if (! preg_match('/^[\pL\pM\pN_-]+$/u', $val)) {
                $pass = false;
            }
        }
        return $pass;
    }

    /**
     * @return array|string|null
     */
    public function message()
    {
        return __('validation.alpha_dash');
    }
}
