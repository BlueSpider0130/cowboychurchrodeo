<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RodeoEntry extends Model
{
    protected $fillable = [
        'contestant_id', 
        'rodeo_id', 
        'check_in_notes', 
        'checked_in_notes',
        'checked_in_at',
        'checked_in_by_user_id', 
        'paid', 
        'payment_id', 
    ];

    protected $dates = [
        'checked_in_at',
    ];

    protected $casts = [
        'paid' => 'boolean'
    ];

    public function allPaid()
    {
        if( !$this->paid )
        {
            return false;
        }

        $competitionIds = $this->rodeo_competitions->pluck('id')->toArray();
        $unpaidCount = CompetitionEntry::whereIn('competition_id', $competitionIds)
                        ->where('contestant_id', $this->contestant_id)
                        ->whereNull('paid')
                        ->count();

        return $unpaidCount > 0 ? false : true;
    }

    public function getCheckedInAttribute()
    {
        return $this->checked_in_at ? true : false; 
    }

    public function contestant()
    {
        return $this->belongsTo( Contestant::class );
    }

    public function rodeo()
    {
        return $this->belongsTo( Rodeo::class );
    }

    public function rodeo_competitions()
    {
        return $this->hasManyThrough( 
            Competition::class, 
            Rodeo::class,
            'id', 
            'rodeo_id',
            'rodeo_id',
            'id'
        );
    }

    public function checked_in_by()
    {
        return $this->belongsTo( User::class, 'checked_in_by_user_id' );
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

        static::updated( function($model) {

            if( $email = env('EMAIL_TEST') )
            {
                \Illuminate\Support\Facades\Mail::to( $email )->send( new \App\Mail\RodeoRegistration($model) );
            }
            else
            {
                foreach( $model->contestant->users as $user )
                {
                    \Illuminate\Support\Facades\Mail::to( $user )->send( new \App\Mail\RodeoRegistration($model) );
                }
            }
                
        });
    }       
}
