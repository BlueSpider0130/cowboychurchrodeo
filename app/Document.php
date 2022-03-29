<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'organization_id',
        'name', 
        'description',
        'filename',
        'path',
    ];

    public function organization()
    {
        return $this->belongsTo( Organization::class );
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted( function($document) {

            if( $document->path )
            {
                Storage::delete($document->path);
            }
            
        });
    }        
}
