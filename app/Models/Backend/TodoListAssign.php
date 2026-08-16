<?php

namespace App\Models\Backend;

use App\Enums\TodoStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoListAssign extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function todo(){
        return $this->belongsTo(TodoList::class,'todo_list_id','id');
    }

    
    public function getMyStatusAttribute(){ 

        if($this->status  == TodoStatus::PENDING){
            return '<span class="badge badge-pill badge-danger">'.__('todoStatus.'.TodoStatus::PENDING).'</span>';
        }elseif($this->status  == TodoStatus::PROCESSING){
            return '<span class="badge badge-pill badge-warning">'.__('todoStatus.'.TodoStatus::PROCESSING).'</span>';
        }elseif($this->status  == TodoStatus::COMPLETED){
            return '<span class="badge badge-pill badge-success">'.__('todoStatus.'.TodoStatus::COMPLETED).'</span>';
        }  
      
    }

}
