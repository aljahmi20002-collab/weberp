<?php


namespace App\Models\Backend;
use App\Enums\Status;
use App\Enums\TodoStatus;
use App\Models\Upload;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Business\Entities\Business;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\Backend\TodoListAssign;
class TodoList extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = ['title', 'description','date','user_id','todo_file','status'];

    public function upload(){
        return $this->belongsTo(Upload::class,'todo_file','id');
    }

    public function getFileAttribute(){
        if($this->upload && !empty($this->upload->original['original']) && File::exists($this->upload->original['original'])):
            return static_asset($this->upload->original['original']);
        endif;
        return '#';
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('TodoList')
        ->logOnly(['title','description','date','user.name','status'])
        ->setDescriptionForEvent(fn(string $eventName) => "{$eventName}");
    }

    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }

    public function todolistAssigned(){
        return $this->hasMany(TodoListAssign::class,'todo_list_id','id');
    }
    public function todolistAssignedCompleted(){
        return $this->hasMany(TodoListAssign::class,'todo_list_id','id')->where('status',TodoStatus::COMPLETED);
    }
    public function todolistAssignedProecessing(){
        return $this->hasMany(TodoListAssign::class,'todo_list_id','id')->where('status',TodoStatus::PROCESSING);
    }
   
    public function singletodoAssigned(){
        return $this->belongsTo(TodoListAssign::class,'id','todo_list_id')->where('user_id',Auth::user()->id);
    }
 
    public function getMyStatusAttribute(){
        if(isUser()){
            if($this->singletodoAssigned && $this->singletodoAssigned->status == TodoStatus::PENDING){
                return '<span class="badge badge-pill badge-danger">'.__('todoStatus.'.TodoStatus::PENDING).'</span>';
            }elseif($this->singletodoAssigned && $this->singletodoAssigned->status == TodoStatus::PROCESSING){
                return '<span class="badge badge-pill badge-warning">'.__('todoStatus.'.TodoStatus::PROCESSING).'</span>';
            }elseif($this->singletodoAssigned && $this->singletodoAssigned->status == TodoStatus::COMPLETED){
                return '<span class="badge badge-pill badge-success">'.__('todoStatus.'.TodoStatus::COMPLETED).'</span>';
            }  
        }else{  
            if($this->todolistAssigned->count() <=0):
                return '<span class="badge badge-pill badge-danger">'.__('todoStatus.'.TodoStatus::PENDING).'</span>';
            elseif($this->todolistAssignedCompleted->count() >= $this->todolistAssigned->count()):
                return '<span class="badge badge-pill badge-success">'.__('todoStatus.'.TodoStatus::COMPLETED).'</span>';
            else:
                $total = $this->todolistAssignedCompleted->count().'/'.$this->todolistAssigned->count(); 
                if($this->todolistAssignedProecessing->count() > 0):
                    $total .= '<br/> Processing:'.$this->todolistAssignedProecessing->count();
                endif; 
                return $total;
            endif;
        }
    }
    public function getMyStatusNameAttribute(){
        
            if($this->todolistAssigned->count() <=0):
                return  __('todoStatus.'.TodoStatus::PENDING);
            elseif($this->todolistAssignedCompleted->count() >= $this->todolistAssigned->count()):
                return  __('todoStatus.'.TodoStatus::COMPLETED);
            else:
                $total = $this->todolistAssignedCompleted->count().'/'.$this->todolistAssigned->count(); 
                if($this->todolistAssignedProecessing->count() > 0):
                    $total .= 'Processing:'.$this->todolistAssignedProecessing->count();
                endif; 
                return $total;
            endif;
       
    }

 
 

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }

}
