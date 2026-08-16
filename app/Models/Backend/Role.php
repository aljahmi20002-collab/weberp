<?php

namespace App\Models\Backend;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Role extends Model
{
    use HasFactory,LogsActivity;
    protected $casts  = ['permissions'=> 'array'];

    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('status.'.$this->status).'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('status.'.$this->status).'</span>';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Role')
        ->logOnly(['name','slug'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }
}
