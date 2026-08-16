<?php

namespace Modules\Support\Entities;

use App\Enums\UserType;
use App\Models\Upload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
use Modules\Department\Entities\Department;
use Modules\Service\Entities\Service;
use Modules\Support\Enums\SupportStatus;

class Support extends Model
{
    use HasFactory;

    protected $fillable = [];
    
   
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }    
    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    } 
    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
    public function attachedFile(){
        return $this->belongsTo(Upload::class,'attached_file','id');
    }

    public function getUserTypeAttribute(){
        if($this->user->user_type == UserType::ADMIN):
            $user_type  = __('admin');
        else:
            $user_type  = __('user');
        endif;
        return $user_type;
    }

    public function getDownloadfileAttribute(){
        if($this->attachedFile && $this->attachedFile && File::exists(public_path($this->attachedFile->original['original']))):
            return static_asset($this->attachedFile->original['original']);
        else:
            return null;
        endif;
    }
    public function getCreatedDateTimeAttribute(){
        return Carbon::parse($this->created_at)->format('d M Y H:i a');
    }

    public function getMyStatusAttribute(){
        $status ='';
        if(SupportStatus::PENDING            == $this->status):
            $status   = '<span class="badge badge-primary">'.__('pending').'</span>';
        elseif(SupportStatus::PROCESSING     == $this->status):
            $status   = '<span class="badge badge-warning">'.__('processing').'</span>';
        elseif(SupportStatus::RESOLVED   == $this->status):
            $status   = '<span class="badge badge-success">'.__('resolved').'</span>';
        elseif(SupportStatus::CLOSED     == $this->status):
            $status   = '<span class="badge badge-danger">'.__('closed').'</span>';
        endif;
        return $status;
    } 

}
