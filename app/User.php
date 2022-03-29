<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 
        'last_name',
        'email', 
        'password', 
        'admin',
        'super',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'operator_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'admin' => 'boolean',
        'super' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'operator_key_expires_at'
    ];

    /**
     * Relations to eager load.
     *
     * @var array
     */
    protected $with = [
        'user_levels'
    ];    

    /**
     * Get full name.
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * If user is an admin user.
     */
    public function getIsAdminAttribute()
    {
        return $this->admin;
    }

    /**
     * If user is a super user.
     */
    public function getIsSuperAttribute()
    {
        return $this->super;
    }

    /**
     * User levels
     */
    public function user_levels()
    {
        return $this->hasMany( UserLevel::class );
    }

    /**
     * Contestants that user "owns" / "can manage".
     */
    public function contestants()
    {
        return $this->belongsToMany( Contestant::class );
    }
    // Payment that user pay for contestants
    public function payment()
    {
        return $this->hasMany( Payment::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('hide-super', function (Builder $builder) {
            $builder->where('super', false)->orWhere( function($q) {
                return $q->where('super', true)->where('admin', true);
            });
        });

        static::deleted(function ($user) {

            // Delete any associated user levels 
            UserLevel::where('user_id', $user->id)->delete();

        });        
    } 

    /**
     * Get full name.
     */
    public function getName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user is super admin.
     */
    public function isSuper(): bool
    {
        return $this->super ? true : false;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->admin ? true : false;
    }

    /**
     * Check if user has any user level with the specified level.
     */
    public function hasLevelForOrganizationId( $level, $organizationId ): bool
    {
        return $this->user_levels->where('level', $level)->where('organization_id', $organizationId)->count() > 0 ? true : false;
    }

    /**
     * Count of organizations user has level access to.
     */
    public function countUserLevels( $level ): int
    {
        return $this->user_levels->where('level', $level)->count();
    }    
    
}
