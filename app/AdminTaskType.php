<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTaskType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public static function boot() 
    {
        parent::boot();

        static::deleting( function ($model) {
            AdminTask::where('type_id', $model->id)->update([
                'type_id' => null,
            ]);
        });
    }    

}
