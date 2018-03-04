<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;

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
        'branch_id', 
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

    public function isAdmin(){

        if ($this->role->name == 'admin'){
            return true;
        }
        return false;
    }

    public function isSuperAdmin(){

        if ($this->role->name == 'superAdmin'){
            return true;
        }
        return false;
    }

    public function isCounterStaff(){

        if ($this->role->name == 'counterStaff'){
            return true;
        }
        return false;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    /**
    * Relationship
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

    public function branchCounter(){
        
        return $this->hasOne(BranchCounter::class, 'staff_id');
    }
}
