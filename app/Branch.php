<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $casts = [
        'service_ids' => 'array',
    ];

    public function branchServices(){

    	return $this->hasMany(BranchService::class);
    }

    public function users(){

    	return $this->hasMany(User::class);
    }
}
