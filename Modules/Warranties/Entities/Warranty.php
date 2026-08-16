<?php

namespace Modules\Warranties\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Business\Entities\Business;
use Modules\Warranties\Enums\WarrantyType;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    } 
    public function business (){
        return $this->belongsTo(Business::class,'business_id','id');
    }

    public function getDurationtypesAttribute(){
        if($this->duration_type == WarrantyType::YEAR):
            return __('year');
        elseif($this->duration_type == WarrantyType::MONTH):
            return __('month');
        else:
            return __('day');
        endif;
    }

}
