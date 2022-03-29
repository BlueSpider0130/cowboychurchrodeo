<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contestant_id', 
        'series_id', 
        'notes',
        'pending',
        'paid', 
        'payment_id', 
    ];

    protected $casts = [
        'paid' => 'boolean'
    ];

    /**
     * The contestant membership is for.
     */
    public function contestant()
    {
        return $this->belongsTo( Contestant::class );
    }

    /**
     * Series membership is for.
     */
    public function series()
    {
        return $this->belongsTo( Series::class );
    }

    /**
     * Payment
     */
    public function payment()
    {
        return $this->belongsTo( Payment::class );
    }

    /**
     * Payment item
     */
    public function payment_item()
    {
        return $this->morphOne( PaymentItem::class, 'paymentable' );
    }
}
