<?php

namespace Modules\Account\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Enums\AccountType;
use Modules\Plan\Enums\IsDefault;

class Account extends Model
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

    public function getMyDefaultAttribute(){
        if($this->is_default == IsDefault::YES):
            return true;
        else:
            return false;
        endif;
    }

  

}
