<?php

if( false === function_exists('rodeo_date_format') )
{
    function rodeo_date_format($date)
    {
        if( $date )
        {
            if( is_string($date) && ctype_digit($date) )
            {
                $date = (int) $date;
            }

            if( is_int($date) )
            {
                $date = \Carbon\Carbon::createFromTimeStamp($date);
            }

            if( is_a($date, \Carbon\Carbon::class) )
            {
                return $date->format('D, M d, Y');
            }
        }
        
        return null;
    }
}