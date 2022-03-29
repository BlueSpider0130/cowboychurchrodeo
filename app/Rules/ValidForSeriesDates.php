<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Series; 

class ValidForSeriesDates implements Rule
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
        $value = \Carbon\Carbon::createFromFormat('Y-m-d', $value)->startOfDay();

        if( $this->series->starts_at  &&  $value < $this->series->starts_at->startOfDay() )
        {
            return false;
        }

        if( $this->series->ends_at  &&  $value > $this->series->ends_at->addDays(1)->startOfDay()->subSeconds(1) )
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
        $message = "The :attribute must be within the series dates";

        if( $this->series->starts_at  &&  $this->series->ends_at )
        {
            $start = $this->series->starts_at->toFormattedDateString();
            $end = $this->series->ends_at->toFormattedDateString();

            $message .= " ({$start} - {$end})";
        }

        $message .= '.';

        return $message;
    }
}
