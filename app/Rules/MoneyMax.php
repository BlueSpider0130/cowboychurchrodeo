<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MoneyMax implements Rule
{
    protected $symbol;
    protected $symbolPosition;
    protected $max;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( $max = 9999999999, $symbol = '$', $symbolPosition = 'prepend' )
    {
        if( !is_numeric($max) )
        {
            throw new \Exception("MoneyMax rule maximum value must be a number.", 1);            
        }

        $this->max = $max;
        $this->symbol = $symbol;
        $this->symbolPosition = in_array($symbolPosition, ['prepend', 'append']) ? $symbolPosition : 'prepend';
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
        $value =  trim($value);
        $value = str_replace(',', '', $value);
        $value = (float) $value;

        return $value <= $this->max ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $number = number_format($this->max, 2);
        $moneyString = 'append' == $this->symbolPosition
                            ? "$number {$this->symbol}"
                            : $this->symbol . $number;

        return "The :attribute cannot be greater than $moneyString";
    }
}
