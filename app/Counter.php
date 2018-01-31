<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    //

    public function branchService(){

    	return $this->belongsTo(BranchService::class);
    }

    public function queues(){

    	return $this->belongsToMany(Queue::class);
    }

    public function servings(){
    	
    	return $this->hasMany(Serving::class);
    }
}
