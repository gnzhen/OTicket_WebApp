<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{

    public function branchCounters(){

    	return $this->hasMany(BranchCounter::class);
    }

    public function branches(){
        return $this->belongsToMany(Branch::class, 'branch_counters')->withPivot('id', 'staff_id');
    }
}
