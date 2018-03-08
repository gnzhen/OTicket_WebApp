<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calling extends Model
{
    public function ticket(){
    	
    	return $this->belongsTo(Ticket::class);
    }

	public function branchCounter(){
    	
    	return $this->belongsTo(BranchCounter::class);
    }

}
