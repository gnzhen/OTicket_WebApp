<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $casts = [
        'ticket_ids' => 'array',
    ];

    public function branchService(){

    	return $this->belongsTo(BranchService::class);
    }

    public function tickets(){
    	
    	return $this->hasMany(Ticket::class)->orderBy('issue_time');
    }

    public function branchCounters(){

        return $this->belongsToMany(branchCounter::class, 'branch_counter_queues')->withPivot('staff_id');
    }
}
