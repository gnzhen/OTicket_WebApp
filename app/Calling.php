<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calling extends Model
{
    public function ticket(){
    	
    	return $this->belongsTo(Ticket::class);
    }

	public function branchCounters(){
    	
    	return $this->belongsTo(BranchCounter::class);
    }

}
