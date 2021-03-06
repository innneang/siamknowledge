<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function interests()
    {
        return $this->hasMany('App\interest');
    }
    public function credit(){
        return $this->hasOne('App\Credit');
    }
    public function  profile(){
        return $this->hasOne('App\Profile');
    }
    public function course()
    {
        return $this->hasMany('App\Course');
    }
    public function creditlog()
    {
        return $this->hasMany('App\creditlog');
    }
}
