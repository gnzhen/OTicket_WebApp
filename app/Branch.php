<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'code',
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

    public function counters(){
        return $this->belongsToMany(Counter::class, 'branch_counters')->withPivot('id', 'staff_id');
    }

    public function services(){
        return $this->belongsToMany(Service::class, 'branch_services')->withPivot('id','system_wait_time', 'default_wait_time');
    }
}
