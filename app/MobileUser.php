<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class MobileUser extends Model
{
    use HasApiTokens;

    public function tickets(){
    	
    	return $this->hasMany(Ticket::class);
    }
}
