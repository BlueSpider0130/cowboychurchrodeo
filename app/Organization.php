<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'type', 
        'name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postcode',
        'country_code',
        'phone',
        'email',

        'admin_notes',

        'site_description',
        'site_title',
        
        'site_logo_path',
        'site_banner_path',
        'site_header_banner_show',

        'site_font_family',
        'site_font_size',
        'site_text_color',
        'site_background_color',

        'site_header_font_family',
        'site_header_font_size',
        'site_header_text_color',
        'site_header_background_color',
        
        'site_footer_show',
        'site_footer_content',
        'site_footer_font_family',
        'site_footer_font_size',
        'site_footer_text_color',
        'site_footer_background_color',
    ];

    public function user_levels()
    {
        return $this->hasMany( UserLevel::class );
    }

    public function users()
    {
        return $this->belongsToMany( User::class, 'user_levels' );
    }

    public function contestants()
    {
        return $this->hasMany( Contestant::class );
    }

    public function documents()
    {
        return $this->hasMany( Document::class );
    }

    public function events()
    {
        return $this->hasMany( Event::class );
    }

    public function groups()
    {
        return $this->hasMany( Group::class );
    }

    public function competitions()
    {
        return $this->hasMany( Competition::class );
    }

    public function rodeos()
    {
        return $this->hasMany( Rodeo::class );
    }

    public function series()
    {
        return $this->hasMany( Series::class );
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($organization) {

            // Delete any associated user levels 
            UserLevel::where('organization_id', $organization->id)->delete();

        });
    }
}
