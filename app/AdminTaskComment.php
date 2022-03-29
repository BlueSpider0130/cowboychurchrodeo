<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTaskComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['task_id', 'user_id', 'body'];

    public function task()
    {
        return $this->belongsTo(AdminTask::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
