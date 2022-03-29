<?php

namespace App;

trait PersonalNameAttributes
{
    /**
     * The contestant full name ( first name last name ).
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * The contestant full name ( first name last name ).
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * The contestant lexical order name ( last name, first name).
     */
    public function getLexicalNameOrderAttribute()
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    /**
     * The contestant lexical order name ( last name, first name).
     */
    public function getEasternNameOrderAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }    
}