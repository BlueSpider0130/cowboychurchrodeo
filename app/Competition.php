<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */    
    protected $fillable = [
        'organization_id',
        'rodeo_id', 
        'event_id', 
        'group_id', 
        'name', 
        'description', 
        'entry_fee', 
        'membership_required',
        'max_entries',
        'max_entries_per_contestant',
        'registration_opens_at', 
        'registration_closes_at', 
    ];

    protected $dates = [
        'starts_at',
        'ends_at',
        'registration_opens_at', 
        'registration_closes_at'
    ];

    protected $casts = [
        'membership_required' => 'boolean',
        'multiple_entries_per_contesant' => 'boolean'
    ];

    /**
     * If multiple entries are allowed.
     */
    public function getAllowMultipleEntriesPerContestantAttribute()
    {
        return 1 !== $this->max_entries_per_contestant;
    }

    /**
     * Name for competition 
     */
    public function getNameAttribute($value)
    {
        return $value ? $value : $this->group->name . ' ' . $this->event->name;
    }

    /**
     * The organization the competition belongs to. 
     */
    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    /**
     * The rodeo competition is part of (if it is part of rodeo).
     */
    public function rodeo()
    {
        return $this->belongsTo( Rodeo::class );
    }

    /**
     * The competition event.
     */
    public function event()
    {
        return $this->belongsTo( Event::class );
    }

    /**
     * The group competition is in.
     */
    public function group()
    {
        return $this->belongsTo( Group::class );
    }

    /**
     * The date/time instances that the competition occurs on.
     */
    public function instances()
    {
        return $this->hasMany( CompetitionInstance::class )->orderBy('starts_at');
    }

    /**
     * Contestant entries into the competition.
     */
    public function entries()
    {
        return $this->hasMany( CompetitionEntry::class );
    }

    /**
     * The contestants that have been entered into the competition. 
     */
    public function contestants()
    {
        return $this->belongsToMany( Contestant::class, 'entries' );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted( function($model) {

            $model->instances()->delete();
            
        });
    } 

    /**
     * 
     */
    public function getInstanceStartTimestamps()
    {
        $timestamps = [];

        foreach ($this->instances as $instance) 
        {
            if( $instance->starts_at )
            {
                $timestamps[] = $instance->starts_at->timestamp;
            }
        }

        return $timestamps;
    }
}
