<?php

namespace App\Models;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Models\Backend\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Enums\AttendanceStatus;
use Modules\Branch\Entities\Branch;
use Modules\Business\Entities\Business;
use Modules\Department\Entities\Department;
use Modules\Designation\Entities\Designation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,LogsActivity;


    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'date_of_birth',
        'gender',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'role_id',
        'permissions',
        'verify_token',
        'business_owner',
        'email_verified',
        'email_verified_at',
        'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('User')
        ->logOnly(['name', 'email','upload.original'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions'       => 'array'
    ];

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function upload(){
        return $this->belongsTo(Upload::class,'avatar','id');
    }

    public function getMyStatusAttribute(){
        if($this->status == Status::ACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('status.'.$this->status).'</span>';
        }elseif($this->status == Status::INACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('status.'.$this->status).'</span>';
        }
    }
    public function getBanTypeAttribute(){
        if($this->is_ban == Status::ACTIVE){
            return '<span class="badge badge-pill badge-danger">'.__('banuser.'.$this->is_ban).'</span>';
        }elseif($this->is_ban == Status::INACTIVE){
            return '<span class="badge badge-pill badge-success">'.__('banuser.'.$this->is_ban).'</span>';
        }
    }
    public function getImageAttribute()
    {
        if (!empty($this->upload->original['original']) && file_exists(public_path($this->upload->original['original']))) {
            return static_asset($this->upload->original['original']);
        }
        return static_asset('backend/images/default/user.jpg');
    }

    public function designation(){
        return $this->belongsTo(Designation::class,'designation_id','id');
    }

    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function ownerbusiness(){
        return $this->belongsTo(Business::class,'id','owner_id');
    } 
    public function userBusiness(){
        return $this->belongsTo(Business::class,'business_id','id');
    }

    public function getBusinessAttribute(){ 
        if($this->ownerbusiness):
            return $this->ownerbusiness;
        elseif($this->userBusiness): 
            return $this->userBusiness;
        endif;
        return null;
    }

    public function userBranch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function getBusinessInfoAttribute(){
       
        if($this->business):
            return $this->business;
        elseif($this->userBusiness):
            return $this->userBusiness;
        endif;
        return null;
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    public function branches(){
        return $this->hasMany(Branch::class,'business_id','business_id');
    }

    public function getUsertypesAttribute(){
        $type = '';
        switch($this->user_type):
            case(\App\Enums\UserType::SUPERADMIN):
                $type = __('superadmin');
            break;
            case(\App\Enums\UserType::ADMIN):
               $type =  __('admin');
            break;
            case(\App\Enums\UserType::USER):
                $type = __('user');
            break; 
        endswitch;

        return $type;
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'employee_id','id');
    }

 

}
