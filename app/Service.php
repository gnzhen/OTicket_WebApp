<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $incrementing = false;

    public function branchServices(){

    	return $this->hasMany(BranchService::class);
    }
}
