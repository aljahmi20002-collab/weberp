<?php

namespace Modules\Branch\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Currency\Entities\Currency;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function country(){
        return $this->belongsTo(Currency::class,'country_id','id');
    }

    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }
}
