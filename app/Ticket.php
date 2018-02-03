<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //

    public function queue(){
    	
    	return $this->belongsTo(Queue::class);
    }

    public function mobileUser(){
    	
    	return $this->belongsTo(MobileUser::class);
    }

    public function serving(){
    	
    	return $this->hasOne(Serving::class);
    }

    public function changes(){
    	
    	return $this->belongsToMany(Change::class);
    }
}
