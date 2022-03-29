<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{   
    protected $fillable = [
        'organization_id',
        'name', 
        'description',
    ];

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    public function competitions()
    {
        return $this->hasMany( Competition::class );
    }

    public function events()
    {
        return $this->belongsToMany( Event::class, 'competitions' );
    }

    public function scopeInRodeo($query, Rodeo $rodeo)
    {
        $ids = Competition::where('rodeo_id', $rodeo->id)->distinct('group_id')->pluck('group_id')->toArray();
        return $query->whereIn('id', $ids);
    }
}
