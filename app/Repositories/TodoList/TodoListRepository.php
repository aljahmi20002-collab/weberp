<?php
namespace App\Repositories\TodoList;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\TodoStatus;
use App\Models\Backend\TodoList;
use App\Models\Backend\TodoListAssign;
use App\Models\Upload;
use App\Models\User;
use App\Repositories\TodoList\TodoListInterface;
use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class TodoListRepository implements TodoListInterface{

    protected $upload;
    public function __construct(UploadInterface $upload)
    {
        $this->upload = $upload;
    }

    public function all(){
        return TodoList::where(function($query){
            if(business()): 
                $query->where('business_id',business_id());
            elseif(isUser()):
                $query->whereHas('todolistAssigned',function($query){
                    $query->where('user_id',Auth::user()->id);
                });
            endif;
        })->orderByDesc('id')->get();
    }
    public function userAllTodo($user_id){
        return  TodoListAssign::with('todo')->where('user_id',$user_id) ->orderByDesc('id')->get();
    }
    public function users(){
        return User::where('is_ban',BanUser::UNBAN)->where('status',Status::ACTIVE)->get();
    }

    public function get($id){
        return TodoList::find($id);
    }

    public function store($request){
        try {
            $todo               = new TodoList();
            if(isSuperadmin()):
                $business_id    = $request->business_id;
            else:
                $business_id    = business_id();
            endif;
            $todo->business_id  = $business_id;
            $todo->project_id   = $request->project_id;
            $todo->title        = $request->title; 
            $todo->date         = $request->date;
            $todo->description  = $request->description;
            // $todo->status       = TodoStatus::PENDING;
            if($request->todoFile):
                $todo->todo_file = $this->upload->upload('todoFile','',$request->todoFile);
            endif;
            $todo->save();

            if($todo):
                foreach ($request->user_id as $user_id) {    
                    $todoAssign                 = new TodoListAssign();
                    $todoAssign->todo_list_id   = $todo->id;
                    $todoAssign->user_id        = $user_id;
                    $todoAssign->save();
                }
            endif;
            return true;
        }
        catch (\Exception $e) { 
            return false;
        }
    }

    public function update($request,$id)
    {
        try {
            $todo               = TodoList::find($id);
            if(isSuperadmin()):
                $business_id    = $request->business_id;
            else:
                $business_id    = business_id();
            endif;
            $todo->business_id  = $business_id;
            $todo->project_id   = $request->project_id;
            $todo->title        = $request->title; 
            $todo->date         = $request->date;
            $todo->description  = $request->description;
            if($request->todoFile):
                $todo->todo_file = $this->upload->upload('todoFile','',$request->todoFile);
            endif;
            $todo->save(); 
            if($todo):
                TodoListAssign::where('todo_list_id',$todo->id)->delete();
                foreach ($request->user_id as $user_id) {    
                    $todoAssign                 = new TodoListAssign();
                    $todoAssign->todo_list_id   = $todo->id;
                    $todoAssign->user_id        = $user_id;
                    $todoAssign->save();
                }
            endif;
            return true;
        }
        catch (\Exception $e) {
            
            return false;
        }
    }


    public function delete($id,$request){
        $todo               = TodoList::find($id);
        if(isUser() || $request->user_id):
            if($request->user_id):
                $user_id   = $request->user_id;
            else:
                $user_id   = Auth::user()->id;
            endif;
            return TodoListAssign::where(['todo_list_id'=>$id,'user_id'=>$user_id])->delete();
        endif;
        if($todo && $todo->upload && File::exists($todo->upload->original)):
            unlink($todo->upload->original);
            Upload::destroy($todo->upload->id);
        endif;
        
        return TodoList::destroy($id);
    }

    public function statusUpdate($id,$request){
        try { 
            $todo                =  TodoListAssign::where(['todo_list_id'=>$id,'user_id'=>$request->user_id])->first();
            if($todo->status     == TodoStatus::PENDING):
                $todo->status    =  TodoStatus::PROCESSING;
            elseif($todo->status == TodoStatus::PROCESSING):
                $todo->status    =  TodoStatus::COMPLETED;
            endif;
            $todo->save(); 
            return true; 
        } catch (\Throwable $th) {  
            return false;
        }
    }
 
    public function totalTodo($user_id){
        $data = [];
        $data ['total_todo']       =    TodoListAssign::where('user_id',$user_id)->count();
        $data ['total_pending']    =    TodoListAssign::where('user_id',$user_id)->where('status',TodoStatus::PENDING)->count();
        $data ['total_processing'] =    TodoListAssign::where('user_id',$user_id)->where('status',TodoStatus::PROCESSING)->count();
        $data ['total_completed']  =    TodoListAssign::where('user_id',$user_id)->where('status',TodoStatus::COMPLETED)->count(); 
        return $data;  
    }
}
