<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $fillable = [
        'payment_id',
        'name', 
        'description', 
        'amount',
        'paymentable_type', 
        'paymentable_id',
    ];

    /**
     * Payment the payment item is for.
     */
    public function payment()
    {
        return $this->belongsTo( Payment::class );
    }

    /**
     * Get the owning paymentable model.
     */
    public function paymentable()
    {
        return $this->morphTo();
    }
}
