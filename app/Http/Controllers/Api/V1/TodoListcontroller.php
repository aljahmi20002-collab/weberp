<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProjectResource;
use App\Http\Resources\Api\V1\TodoListResource;
use App\Http\Resources\v1\UserResource;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\TodoList\TodoListInterface;
use App\Repositories\User\UserInterface;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Modules\Business\Repositories\BusinessInterface;
use App\Http\Requests\Todolist\StoreRequest; 
class TodoListcontroller extends Controller
{
    use ApiReturnFormatTrait;
    
    protected $repo,$userRepo,$businessRepo,$projectRepo;

    public function __construct(TodoListInterface $repo,UserInterface $userRepo,BusinessInterface $businessRepo, ProjectInterface $projectRepo)
    {
        $this->repo          = $repo;
        $this->userRepo      = $userRepo;
        $this->businessRepo  = $businessRepo;
        $this->projectRepo   = $projectRepo;
    }
    public function index()
    { 
        $todoLists  = TodoListResource::collection($this->repo->all());
        return $this->responseWithSuccess(__('success'),[
            'todo_lists' => $todoLists
        ],200); 
    }
  
    public function create()
    {
        $users      = UserResource::collection($this->userRepo->getUsers()); 
        $projects   = ProjectResource::collection($this->projectRepo->getAll());
        return $this->responseWithSuccess(__('success'),[
            'users'  => $users,
            'projects' => $projects
        ],200);
    }
 
    public function edit($id){
       
        $todoList        = $this->repo->get($id); 
        $users           = $this->userRepo->getUsers(); 
        $projects        = $this->projectRepo->getAll();
        $tdassignedUsers = $todoList->todolistAssigned->pluck('user_id');
        return $this->responseWithSuccess(__('success'),[
            'todo'             => $todoList,
            'users'            => $users,
            'projects'         => $projects,
            'assigned_users_id'=>$tdassignedUsers
        ],200);
    }
    public function details($id){ 
        $todo        = $this->repo->get($id);  
        return $this->responseWithSuccess(__('success'),[
            'todo'       => new TodoListResource($todo)
        ],200);
    }
     
    public function store(StoreRequest $request)
    {
        
        if($this->repo->store($request)){ 
            return $this->responseWithSuccess(__('todo_added_msg'),[],200);
        }else{ 
            return $this->responseWithError( __('todo_error_msg'),[],200);
        }

    }

  
    public function update(StoreRequest $request)
    {
       
        if($this->repo->update($request,$request->id)){ 
            return $this->responseWithSuccess(__('todo_update_msg'),[],200);
        }else{ 
            return $this->responseWithError( __('todo_error_msg'),[],200);
        }
    }
 
    public function destroy(Request $request,$id)
    {
        
        if($this->repo->delete($id,$request)): 
            return $this->responseWithSuccess(__('todo_deleted_msg'),[],200);
        else:
            return $this->responseWithError( __('todo_error_msg'),[],200);
        endif;
       
    }
 
    
}
