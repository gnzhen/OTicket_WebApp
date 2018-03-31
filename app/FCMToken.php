<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FCMToken extends Model
{
	protected $table = 'FCMTokens';

    public function mobileUser(){

    	return $this->belongsTo(MobileUser::class);
    }
}
