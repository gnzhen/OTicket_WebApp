<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'id',
        'name',
        'desc',
    ];

    protected $casts = [
        'service_ids' => 'array',
    ];

    public $incrementing = false;

    public function branchServices(){

    	return $this->hasMany(BranchService::class);
    }

    public function branchCounters(){

    	return $this->hasMany(BranchCounter::class);
    }

    public function users(){

    	return $this->hasMany(User::class);
    }
}
