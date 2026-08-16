<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [];
 
    public function plan(){
        return $this->belongsTo(Plan::class,'plan_id','id');
    }

    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
}
