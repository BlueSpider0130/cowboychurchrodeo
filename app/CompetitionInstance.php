<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionInstance extends Model
{   
    protected $fillable = [
        'competition_id', 
        'description',
        'location',
        'all_day', 
        'starts_at', 
        'ends_at'
    ];

    protected $dates = [
        'starts_at', 
        'ends_at',
    ];

    protected $casts = [
        'all_day'
    ];

    public function competition()
    {
        return $this->belongsTo( Competition::class );
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

            CompetitionEntry::where('instance_id', $model->id)->update([ 'instance_id' => null ]);
            
        });
    }       
}
