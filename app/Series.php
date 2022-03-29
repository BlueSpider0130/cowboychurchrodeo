<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model implements ScopeableStartEnd
{   
    use StartEndScopes;
    use RodeoDatesTrait;
    
    protected $fillable = [
        'organization_id',
        'name', 
        'description',           
        'starts_at', 
        'ends_at',
        'membership_fee',
        'draft'
    ];

    protected $dates = [
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'membership_required' => 'boolean'
    ];

    public function getDisplayNameAttribute()
    {
        return $this->name ? $this->name : 'Series #'.$this->id;
    }

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    public function documents()
    {
        return $this->belongsToMany( Document::class, 'document_series' );
    }

    public function rodeos()
    {
        return $this->hasMany( Rodeo::class ); 
    }

    public function memberships()
    {
        return $this->hasMany( Membership::class );
    }
}
