<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{   
    protected $fillable = [
        'organization_id',
        'name', 
        'description',
        'team_roping', 
        'result_type'
    ];

    protected $casts = [
        'team_roping' => 'boolean'
    ];

    /**
     * Is team roping.
     */
    public function getIsTeamRopingAttribute()
    {
        return $this->team_roping;
    }

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    public function competitions()
    {
        return $this->hasMany( Competition::class );
    }

    public function groups()
    {
        return $this->belongsToMany( Group::class, 'competitions' );
    }
}
