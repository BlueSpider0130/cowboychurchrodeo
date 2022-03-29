<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount', 
        'tax', 
        'notes', 
        'method', 
        'payer_user_id', 
        'created_by_user_id'
    ];

    // public function contestant()
    // {
    //     return $this->belongsTo( Contestant::class);
    // }
}
