<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    public function interests()
    {
        return $this->hasMany('App\interest');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
