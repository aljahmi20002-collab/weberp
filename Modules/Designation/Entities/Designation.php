<?php

namespace Modules\Designation\Entities;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
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
}
