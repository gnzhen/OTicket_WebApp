<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BranchService extends Model
{
    public function branch(){

    	return $this->belongsTo(Branch::class);
    }

    public function service(){

    	return $this->belongsTo(Service::class);
    }

    public function queues(){

    	return $this->hasMany(Queue::class);
    }

    public function active_queue(){

    	return $this->hasMany(Queue::class)->where('active','=', 1);
    }

    public function inactive_queue(){

        return $this->hasMany(Queue::class)->where('active','=', 0);
    }
}
