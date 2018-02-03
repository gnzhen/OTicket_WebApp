<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serving extends Model
{
    //
    public function counter(){

    	return $this->belongsTo(Counter::class);
    }

    public function ticket(){

    	return $this->belongsTo(Ticket::class);
    }

    public function user(){

    	return $this->belongsTo(User::class);
    }
}
