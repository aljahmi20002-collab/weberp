<?php

namespace Modules\AccountHead\Entities;

use App\Enums\StatementType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;

class AccountHead extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function getMyTypeAttribute(){
        if($this->type == StatementType::INCOME){
            return '<span class="badge badge-pill badge-success">'.__(Config::get('pos_default.statement_type.'.$this->type)).'</span>';
        }elseif($this->type == StatementType::EXPENSE){
            return '<span class="badge badge-pill badge-danger">'.__(Config::get('pos_default.statement_type.'.$this->type)).'</span>';
        }
    }
    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('active').'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('inactive').'</span>';
        }
    }
}
