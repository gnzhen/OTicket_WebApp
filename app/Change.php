<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    //
    public function tickets(){
    	
    	return $this->belongsToMany(Ticket::class);
    }
}
