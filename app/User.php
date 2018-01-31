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
        'username', 
        'role_id', 
        'email', 
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * Get all the user for a role.
    */
    public function role(){

        return $this->belongsTo(Role::class);
    }

    public function branch(){

        return $this->belongsTo(Branch::class);
    }

    public function servings(){
        
        return $this->hasMany(Serving::class);
    }
}
