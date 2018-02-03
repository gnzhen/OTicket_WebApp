<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    //
    public function branchService(){

    	return $this->belongsTo(BranchService::class);
    }

    public function branchCounters(){

    	return $this->belongsToMany(BranchCounter::class);
    }

    public function tickets(){
    	
    	return $this->hasMany(Ticket::class);
    }
}
