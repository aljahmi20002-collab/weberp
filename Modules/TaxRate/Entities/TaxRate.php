<?php

namespace Modules\TaxRate\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Business\Entities\Business;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [];
    public function business(){
        return $this->belongsTo(Business::class, 'business_id','id');
    }
    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }
}
