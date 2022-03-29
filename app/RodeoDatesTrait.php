<?php

namespace App;

trait RodeoDatesTrait
{
    public function formattedStartDate()
    {
        if( $this->starts_at && is_a($this->starts_at, \Carbon\Carbon::class) )
        {
            return rodeo_date_format($this->starts_at);
        }
    }

    public function formattedEndDate()
    {
        if( $this->ends_at && is_a($this->ends_at, \Carbon\Carbon::class) )
        {
            return rodeo_date_format($this->ends_at);
        }
    }
}
