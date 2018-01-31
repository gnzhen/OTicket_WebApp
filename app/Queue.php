<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    //
    public function branchService(){

    	return $this->hasOne(BranchService::class);
    }

    public function counters(){

    	return $this->belongsToMany(Counter::class);
    }

    public function tickets(){
    	
    	return $this->hasMany(Ticket::class);
    }
}
