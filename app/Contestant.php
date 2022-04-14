<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Contestant extends Model
{
    use PersonalNameAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'first_name', 
        'last_name', 
        'birthdate', 
        'sex',
        'photo_path', 
        'address_line_1', 
        'address_line_2', 
        'city', 
        'state', 
        'postcode',
        'phone',
        'is_member',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birthdate'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [ 
        'is_member' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [ 'name', 'full_name', 'lexical_name_order', 'eastern_name_order' ];

    /**
     * The organization contestant is for.
     */
    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    /**
     * Users that "own" / "can manage" contestant.
     */
    public function users()
    {
        return $this->belongsToMany( User::class );
    }  

    // public function payment()
    // {
    //     return $this->hasOne( Payment::class);
    // }

    /**
     * Series memberships. 
     */
    public function memberships()
    {
        return $this->hasMany( Membership::class );
    }

    /**
     * Contestant's rodeo entries.
     */
    public function rodeo_entries()
    {
        return $this->hasMany( RodeoEntry::class );
    }

    /**
     * Rodeos entered into.
     */
    public function rodeos()
    {
        return $this->belongsToMany( Rodeo::class, 'rodeo_entries' );
    }

    /**
     * Contestants competitions entries.
     */
    public function competition_entries()
    {
        return $this->hasMany( CompetitionEntry::class );
    }

    /**
     * Competitions contestant in entered into.
     */
    public function competitions()
    {
        return $this->belongsToMany( Competition::class, 'competition_entries' );
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::updated( function($model) {

            if( $model->photo_path != $path = $model->getOriginal()['photo_path'] )
            {
                Storage::disk('public')->delete($path);
            }

        });

        static::deleted( function($model) {

            if( $path = $model->photo_path )
            {
                Storage::disk('public')->delete($path);
            }
            
        });
    }       
}
