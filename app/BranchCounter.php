<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BranchCounter extends Model
{   
    public function branch(){

    	return $this->belongsTo(Branch::class);
    }

    public function counter(){

    	return $this->belongsTo(Counter::class);
    }

    public function servings(){
    	
    	return $this->hasMany(Serving::class);
    }

    public function user(){
    	
    	return $this->belongsTo(User::class);
    }

    public function callings(){
        
        return $this->hasMany(Calling::class);
    }

    public function queues(){
        return $this->belongsToMany(Queue::class, 'branch_counter_queues')->withPivot('staff_id');
    }

    public function active_callings(){

        return $this->hasMany(Calling::class)->where('active','=', 1);
    
    }
}
