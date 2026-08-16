<?php

namespace Modules\Variation\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Business\Entities\Business;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $casts    = ['value'=>'array'];

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
     
    public function getVariationValuesAttribute(){
        if($this->value):
            $variation = '';
            $count  = count(array_filter($this->value,function($value){ return $value !=''? $value:'';}));
            $values = array_filter($this->value,function($value){ return $value !=''? $value:'';});

            if(!blank($values)):
                foreach ($values as $key => $value) { 
                    ++$key;
                    $variation  .= $value;
                    if($key < $count):
                        $variation  .=', ';
                    endif; 
                }
            endif;
            return $variation;
        endif;
        return '';
    }
}
