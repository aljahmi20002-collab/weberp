<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todolist\StoreRequest;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use App\Repositories\TodoList\TodoListInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Business\Repositories\BusinessInterface;
use Yajra\DataTables\DataTables;

class TodoListController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return view('backend.todo-list.index');
    }
    
    public function getAllTodoList(){
        $todoLists  = $this->repo->all();
        return DataTables::of($todoLists)
        ->addIndexColumn()
            
        ->editColumn('title',function($todoList){
            return '<a class="text-primary" href="'. route('todoList.view',$todoList) .'">'. @$todoList->title .'</a>';
        })
        ->editColumn('project',function($todoList){
            return '<a class="text-primary" href="'. route('todoList.view',[$todoList,'project'=>'project']) .'">'. @$todoList->project->title .'</a>';
        })
        ->editColumn('file',function($todoList){
            return '<a href="'.$todoList->file .'" download="" >'. __('download') .'</a>';
        })
        ->editColumn('user',function($todoList){
            $userDetails = '';
            $userDetails .= '<div class="d-flex multipleuser">';
            foreach ($todoList->todolistAssigned as $key=>$assigned):
                if($key < 3): 
                    $userDetails .= '<a href="'. route('user.view',$assigned->user_id) .'" class="d-inline-block">';
                    $userDetails .= '<img src="'. $assigned->user->image .'" class="rounded-circle userimg'. $key+1 .'" width="50px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.$assigned->user->name.'" data-bs-html="true" data-bs-title="<b>'. $assigned->user->name .'</b> <br> <i> <small>'. $assigned->user->email .'</small></i>"
                        />';
                        $userDetails .= '</a>';
                endif;
            endforeach;
            if ($todoList->todolistAssigned->count() >3):
                $userDetails .= '<a href="'. route('todoList.view',$todoList) .'" class="d-inline-block bg-red rounded-circle userimg4" >+ '. ($todoList->todolistAssigned->count() -3) .'</a>';
            endif;
            $userDetails .= '</div>';
            return $userDetails;

        })
        ->editColumn('date',function($todoList){
            return @dateFormat($todoList->date);
        })
        ->editColumn('status',function($todoList){
            return  $todoList->mystatus;
        })
        ->editColumn('status_update',function($todoList){
            $statusUpdate  = '';
            if(isUser()):
                if(hasPermission('todo_statusupdate')): 
                    if($todoList->singletodoAssigned && $todoList->singletodoAssigned->status == \App\Enums\TodoStatus::COMPLETED):
                        $statusUpdate  .= '<i class="fa fa-ellipsis"></i>';
                    else:
                        $statusUpdate  .= '<div class="dropdown ">';
                        $statusUpdate  .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $statusUpdate  .= '<i class="fa fa-cogs"></i>';
                        $statusUpdate  .= '</a>';
                        $statusUpdate  .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        $statusUpdate  .= '<a class="dropdown-item" href="'.route('todoList.status.update',[$todoList->id,'user_id'=>Auth::user()->id]) .'">';
                            if($todoList->singletodoAssigned && $todoList->singletodoAssigned->status == \App\Enums\TodoStatus::PENDING):
                                $statusUpdate  .= __('processing');
                            elseif($todoList->singletodoAssigned && $todoList->singletodoAssigned->status == \App\Enums\TodoStatus::PROCESSING):
                                $statusUpdate  .= __('completed');
                            endif;
                            $statusUpdate  .= '</a>';
                            $statusUpdate  .= '</div>';
                            $statusUpdate  .= '</div>';
                    endif;
                else:
                    return '<i class="fa fa-ellipsis"></i>';
                endif; 
            else:
                return '<i class="fa fa-ellipsis"></i>';
            endif;
            return $statusUpdate;
        })
        ->editColumn('action',function($todoList){
            $action  = ''; 
            $action .= '<div class="dropdown">';
            $action .=  '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .=  '<i class="fa fa-cogs"></i>';
            $action .=  '</a>';
            $action .=  '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';

            $action .=  '<a href="'. route('todoList.view',$todoList) .'" class="dropdown-item"   ><i class="fas fa-eye"></i>'.__('view').'</a>';
                if (hasPermission('todo_update')):
                    $action .=  '<a href="'. route('todoList.edit',$todoList) .'" class="dropdown-item"   ><i class="fas fa-pen"></i>'.__('edit').'</a>';
                endif;
                if (hasPermission('todo_delete')):
                    $action .=  '<form action="'. route('todoList.delete',$todoList) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_todo') .'">';
                    $action .= method_field('delete');
                    $action .= csrf_field();
                    $action .=  '<button type="submit" class="dropdown-item" >';
                    $action .=  '<i class="fas fa-trash-alt"></i>'.__('delete');
                    $action .=  '</button>';
                    $action .=  '</form>';
                endif;
                $action .= '</div>';
                $action .= '</div>';
            return $action;
        })  

        ->rawColumns(['title', 'project', 'file', 'user', 'date', 'status',  'status_update', 'action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $users      = $this->userRepo->getUsers();
        $businesses = $this->businessRepo->getAll();
        $projects   = $this->projectRepo->getAll();
        return view('backend.todo-list.create',compact('users','businesses','projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id){
       
        $todoList      = $this->repo->get($id); 
        $users         = $this->userRepo->getUsers();
        $businesses    = $this->businessRepo->getAll();
        $projects      = $this->projectRepo->getAll();
        $tdassignedUsers = $todoList->todolistAssigned->pluck('user_id');
        return view('backend.todo-list.edit', compact('todoList','users','businesses','projects','tdassignedUsers'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('todo_added_msg'),__('success'));
            return redirect()->route('todoList.index');
        }else{
            Toastr::error(__('todo_error_msg'),__('errors'));
            return redirect()->back();
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(StoreRequest $request,$id)
    {
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request,$id)){
            Toastr::success(__('todo_update_msg'),__('success'));
            return redirect()->route('todoList.index');
        }else{
            Toastr::error(__('todo_error_msg'),__('errors'));
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request,$id)
    {
        if(env('DEMO')) {
            Toastr::error(__('Delete system is disable for the demo mode.'),__('errors'));
            return redirect()->back();
        }
        if($this->repo->delete($id,$request)):
            Toastr::success(__('todo_deleted_msg'),__('success'));
            return redirect()->back();
        else:
            Toastr::error(__('todo_error_msg'),__('errors'));
            return redirect()->back();
        endif;
       
    }

    public function statusUpdate(Request $request,$id)
    {
        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id,$request)){
            Toastr::success(__('todo_update_msg'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('todo_error_msg'),__('errors'));
            return redirect()->back();
        }
    }

    public function BusinessUsers(Request $request){
     
        $userData   ='';
        $users  = $this->userRepo->BusinessUsers($request->business_id); 
        foreach ($users as  $user) {
            $userData  .= '<option value="'.$user->id.'">'.$user->name.'</option>';
        } 
        return $userData;
    }

    public function view(Request $request,$id){
        $todolist   = $this->repo->get($id);
        return view('backend.todo-list.view',compact('todolist','request'));
    }
}
