<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchService extends Model
{
    //

    public function branch(){

    	return $this->belongsTo(Branch::class);
    }

    public function service(){

    	return $this->belongsTo(Service::class);
    }

    public function queue(){

    	return $this->hasOne(Queue::class);
    }
}
