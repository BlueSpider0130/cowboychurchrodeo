<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MoneyFormat implements Rule
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
        $value = str_replace(',', '', trim($value));

        if( !is_numeric($value) )
        {
            return false;
        }

        if( false === stripos($value, '.') )
        {
            return true;
        }

        $parts = explode('.', $value);

        if( 1 == count($parts) )
        {
            return true;
        }

        if( count($parts) > 2 )
        {
            return false;
        }

        if( strlen($parts[1]) > 2 )
        {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid.';
    }
}
