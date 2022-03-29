<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status_id',
        'type_id', 
        'priority_id', 
        'created_by_user_id', 
        'page', 
        'description', 
        'closed'
    ];


    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(AdminTaskType::class);
    }

    public function status()
    {
        return $this->belongsTo(AdminTaskStatus::class);
    }

    public function priority()
    {
        return $this->belongsTo(AdminTaskPriority::class);
    }

    public function comments()
    {
        return $this->hasMany(AdminTaskComment::class, 'task_id');
    }

    public function scopeClosed($query)
    {
        return $query->where('closed', 1);
    }

    public function scopeOpen($query)
    {
        return $query->where('closed', 0);
    }
}
