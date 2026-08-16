<?php

namespace App\Models\Backend;

use App\Models\Upload;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    use HasFactory,LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Project')
        ->logOnly(['title','description','date'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }
    public function upload(){
        return $this->belongsTo(Upload::class,'file','id');
    }
    public function getUploadedFileAttribute(){
        if($this->upload && !empty($this->upload->original['original']) && File::exists($this->upload->original['original'])):
            return static_asset($this->upload->original['original']);
        endif;
        return static_asset('/');
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    } 
    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
}
