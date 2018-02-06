<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    public $incrementing = false;

    public function branchCounters(){

    	return $this->hasMany(BranchCounter::class);
    }
}
