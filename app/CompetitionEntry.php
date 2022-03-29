<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionEntry extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = [ 
        'contestant_id', 
        'competition_id', 
        'instance_id', 
        'position', 
        'requested_teammate', 
        'no_fee', 
        'no_score', 
        'paid', 
        'payment_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'no_fee' => 'boolean', 
        'no_score' => 'boolean',
        'paid' => 'boolean'        
    ];

    public function isHeader()
    {
        return 'header' == strtolower($this->position);
    }

    public function isHeeler()
    {
        return 'heeler' == strtolower($this->position);
    }

    /**
     * The contestant the entry is for.
     */
    public function contestant()
    {
        return $this->belongsTo( Contestant::class );
    }  

    /**
     * The competition the entry is for.
     */
    public function competition()
    {
        return $this->belongsTo( Competition::class );
    }

    /**
     * The instance entry registered for.
     */
    public function instance()
    {
        return $this->belongsTo( CompetitionInstance::class, 'instance_id' );
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
    
    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted( function($model) {

            TeamRopingEntry::where('header_entry_id', $model->id)
                ->orWhere('heeler_entry_id', $model->id)
                ->delete();
                
        });
    }     
}
