<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $incrementing = false;

    public function branchServices(){

    	return $this->hasMany(BranchService::class);
    }


    public function branches(){
        return $this->belongsToMany(Branch::class, 'branch_services')->withPivot('id', 'system_wait_time', 'default_wait_time');
    }
}
