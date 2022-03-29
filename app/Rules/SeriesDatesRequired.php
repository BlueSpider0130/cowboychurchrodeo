<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Series;

class SeriesDatesRequired implements Rule
{
    protected $series;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( Series $series )
    {
        $this->series = $series;
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
        return $this->series->starts_at  &&  $this->series->ends_at  ?  true  :  false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
         return 'The series dates must be set before the :attribute can be set.';
    }
}
