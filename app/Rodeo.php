<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rodeo extends Model implements ScopeableStartEnd
{
    use StartEndScopes;
    use RodeoDatesTrait;

    protected $fillable = [
        'organization_id',
        'series_id', 
        'name', 
        'description', 
        'office_fee', 
        'starts_at', 
        'ends_at', 
        'opens_at',
        'closes_at',
        'membership_required',
        'published_at'
    ];

    protected $dates = [
        'starts_at',
        'ends_at',
        'opens_at', 
        'closes_at'
    ];

    protected $casts = [
        'membership_required' => 'boolean'
    ];

    public function nameAttribute($value)
    {
        return $value ? $value : "Rodeo #{$this->id}";
    }

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    public function series()
    {
        return $this->belongsTo( Series::class );
    }

    public function group_office_fee_exceptions()
    {
        return $this->morphedByMany( Group::class, 'not_applicable', 'rodeo_office_fee_exceptions' );
    }

    public function event_office_fee_exceptions()
    {
        return $this->morphedByMany( Event::class, 'not_applicable', 'rodeo_office_fee_exceptions' );
    }

    public function competitions()
    {
        return $this->hasMany( Competition::class );
    }

    public function competition_events()
    {
        return $this->belongsToMany( Event::class, 'competitions' );
    }

    public function competition_groups()
    {
        return $this->belongsToMany( Group::class, 'competitions' );
    }

    public function competition_instances()
    {
        return $this->hasManyThrough( CompetitionInstance::class, Competition::class );
    }

    public function entries()
    {
        return $this->hasMany( RodeoEntry::class );
    }

    public function contestants()
    {
        return $this->belongsToMany( Contestant::class, 'rodeo_entries');
    }

    public function competition_entries()
    {
        return $this->hasManyThrough( CompetitionEntry::class, Competition::class );
    }
 
   public function scopeRegistrationOpen($query)
    {
        return $query
                ->where( 'starts_at', '>', \Carbon\Carbon::now() )
                ->where( function($q) {
                    return $q
                            ->whereNull( 'closes_at' )
                            ->orWhere( 'closes_at', '>=', \Carbon\Carbon::now() );                    
                });
    }

    public function scopeRegistrationClosed($query)
    {
        return $query
                ->where( 'starts_at', '<=', \Carbon\Carbon::now() )
                ->orWhere( function($q) {
                    return $q
                            ->whereNull( 'closes_at' )
                            ->orWhere( 'closes_at', '<=', \Carbon\Carbon::now() );
                });
    }


    public function hasEnded()
    {
        return $this->ends_at  &&  $this->ends_at->copy()->endOfDay() < \Carbon\Carbon::now();  
    }

    public function hasStarted()
    {
        return $this->starts_at  &&  $this->starts_at < \Carbon\Carbon::now();
    }

    /**
     * If rodeo is open and can register for competitions
     */
    public function isRegistrationOpen()
    {
        // Cannot register if ended 
        if( $this->hasEnded() )
        {
            return false;
        }        

        // Cannot register if rodeo has already started
        if( $this->hasStarted() )
        {
            return false;
        }

        // Cannot register if closed
        if( $this->closes_at  &&  $this->closes_at < \Carbon\Carbon::now() )
        {
            return false;
        }

        // Cannot register if not open yet
        if( $this->opens_at && $this->opens_at > \Carbon\Carbon::now() )
        {
            return false;
        }

        return true;
    }  

    /**
     * If rodeo is open and can register for competitions
     */
    public function isRegistrationClosed()
    {
        return false === $this->isRegistrationOpen();
    }


    public function scopeCurrent($query)
    {
        return $query
                ->whereNotNull('starts_at')
                ->whereNotNull('ends_at')
                ->where('starts_at', '>', \Carbon\Carbon::now()->copy()->subDays(5)->startOfDay())
                ->where('ends_at', '<', \Carbon\Carbon::now()->copy()->addDays(1)->endOfDay());
    }
}




