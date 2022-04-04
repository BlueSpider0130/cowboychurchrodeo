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
        'created_by_user_id',
        // 'contestant_id',
    ];


    public function items()
    {
        return $this->hasMany( PaymentItem::class );
    }
}
