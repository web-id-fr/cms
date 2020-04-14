<?php

namespace Webid\Cms\Src\App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslatableMax implements Rule
{
    /**
     * Maximal number of characters
     *
     * @var integer
     */
    public $nbCharacters;

    /**
     * Create a new rule instance.
     *
     * @param  int  $nbCharacters
     * @return void
     */
    public function __construct(int $nbCharacters)
    {
        $this->nbCharacters = $nbCharacters;
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
            if (strlen($val) > $this->nbCharacters) {
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
        return 'The :attribute field must be under ' . $this->nbCharacters . ' characters.';
    }
}
