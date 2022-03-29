<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id', 
        'feedback',
        'page', 
        'email', 
        'response_requested'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
