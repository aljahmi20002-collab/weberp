<?php

namespace App\Http\Controllers\Backend;

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\TodoStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\Role\RoleInterface;
use App\Repositories\TodoList\TodoListInterface;
use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Repositories\BusinessInterface;
use Modules\Department\Repositories\DepartmentInterface;
use Modules\Designation\Repositories\DesignationInterface;
use Modules\Reports\Repositories\AttendanceReport\AttendanceReportInterface;
use Modules\Subscription\Entities\Subscription;
use Yajra\DataTables\DataTables;
class UserController extends Controller
{
    protected  $repo,$role,$businessRepo,$branchRepo,$todolistRepo,$attendanceReportRepo,$designationRepo,$departmentRepo;
    public function __construct(
        UserInterface $repo,
        RoleInterface $role,
        BusinessInterface $businessRepo,
        BranchInterface $branchRepo,
        DesignationInterface $designationRepo,
        DepartmentInterface $departmentRepo,
        TodoListInterface   $todolistRepo,
        AttendanceReportInterface $attendanceReportRepo
    )
    {
        $this->repo             = $repo;
        $this->role             = $role;
        $this->businessRepo     = $businessRepo;
        $this->branchRepo       = $branchRepo;
        $this->designationRepo  = $designationRepo;
        $this->departmentRepo   = $departmentRepo;
        $this->todolistRepo     = $todolistRepo;
        $this->attendanceReportRepo = $attendanceReportRepo;
    }
    public function index(){

        $users      = $this->repo->get();
        return view('backend.user.index',compact('users'));
    }

    public function getAll(){
        $users      = $this->repo->getAll();
        return Datatables::of($users)
        ->addIndexColumn()
        ->editColumn('details',function($user){
            $details = '';
            $details .= '<div class="row d-flex text-left">';
            $details .= '<div class="col-lg-2">';
            $details .= '<img class="rounded-circle" src="'.@$user->image .'" width="60"/>';
            $details .= '</div>';
            $details .= '<div class="col-lg-10">';
            $details .= '<strong>'.@$user->name.'</strong>';
            $details .= '<p class="mb-0">'.@$user->email.'</p>';
            if($user->user_type == UserType::ADMIN):
            $details .= '<p><small>'.@$user->business->business_name.'</small></p>';
            endif;
            $details .= '</div>';
            $details .= '</div>';
            return $details;
        })
        ->editColumn('branch',function($user){
            if(!isSuperadmin()): 
                if ($user->user_type == UserType::USER):
                    return @$user->branch->name;
                else:
                    return '';
                endif;
            else:
                return '...';
            endif;
        })
        ->editColumn('user_type',function($user){
            
            switch($user->user_type){
                case(UserType::SUPERADMIN):
                    return  __('superadmin');
                break;
                case(UserType::ADMIN):
                    return __('admin');
                break;
                case(UserType::USER):
                    return  __('user');
                break;
                default:
                break;
            }
        })
        ->editColumn('role',function($user){
            return @$user->role->name;
        })
        ->editColumn('designation',function($user){
            return @$user->designation->name;
        })
        ->editColumn('department',function($user){
            return @$user->department->name;
        })
        ->editColumn('permissions',function($user){
            return '<span class="badge badge-pill badge-primary permission-count">'.count($user->permissions).'</span>';
        })
        ->editColumn('status',function($user){
            $status = '';
            if(hasPermission('user_ban_unban') || hasPermission('user_status_update')):
            $status .= '<div class="userstatus">';
            if (hasPermission('user_ban_unban')):
                $status .= '<div class="pt-2">';
                $status .= '<div class="d-flex">';
                $status .= '<input type="checkbox" class="banunban"';
                $status .= 'id="banunban'.$user->id .'" switch="danger"';
                $status .= 'data-url="'.route('user.ban.unban',$user->id).'"'; 
                $status .= $user->is_ban ==  BanUser::BAN ? 'checked' : '';
                $status .= '>';
                $status .= '<label for="banunban'. $user->id.'" data-on-label="'. __('banuser.'.BanUser::BAN).'" data-off-label="'.__('banuser.'.BanUser::UNBAN).'"></label>';
                $status .= '</div>';
                $status .= '</div>';
            endif;
            if (hasPermission('user_status_update')):
                $status .= '<div class="pt-2">';
                $status .= '<div class="d-flex">';
                $status .= '<input type="checkbox" class="status"';
                $status .= 'id="status'.$user->id.'"';
                $status .= 'data-url="'.route('user.status.change',$user->id).'"';
                $status .= 'switch="success"';
                $status .= $user->status == Status::ACTIVE ? 'checked' : '';
                $status .= '>';
                $status .= '<label for="status'.$user->id.'" data-on-label="'.__('status.'.Status::ACTIVE).'" data-off-label="'. __('status.'.Status::INACTIVE).'"></label>';
                $status .= '</div>';
                $status .= '</div>';
            endif;
            $status .= '</div>';
        else:
            return '...';
        endif;
            return $status;
        }) 
        ->editColumn('action',function($user){
                if(hasPermission('user_update')  ||
                hasPermission('user_delete')   ||
                hasPermission('user_permissions')): 

                    $action = '';
                    $action .= '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .=  '<i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    $action .= '<a href="'. route('user.view',$user->id).'" class="dropdown-item"   ><i class="fas fa-eye"></i>'.__('view').'</a>';
                            
                    if(hasPermission('user_permissions')):
                        $action .=  '<a href="'.route('user.permissions',$user->id).'" class="dropdown-item"   ><i class="fas fa-key"></i>'. __('permissions').'</a>';
                    endif;
                    if (hasPermission('user_update')):
                    $action .= '<a href="'. route('user.edit',$user->id).'" class="dropdown-item"   ><i class="fas fa-pen"></i>'. __('edit').'</a>';
                    endif;
                    if(Auth::user()->id !== $user->id):
                        if (hasPermission('user_delete') && $user->id !== 1):
                            $action .= '<form action="'.route('user.delete',$user->id).'" method="post" class="delete-form" id="delete" data-yes='. __('yes').' data-cancel="'. __('cancel').'" data-title="'.__('delete_user').'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .= '<button type="submit" class="dropdown-item"  >';
                            $action .= '<i class="fas fa-trash-alt"></i>'.__('delete');
                            $action .= '</button>';
                            $action .= '</form>';
                        endif;
                    endif;
                        
                    $action .= '</div>';
                    $action .= '</div>';
                    return $action;
            else:
                return '...';
            endif;
        })
        ->rawColumns(['details','user_type','role','designation','department','permissions','status','action'])
        ->make(true);
    }

    public function create(){
        $roles      = $this->role->all();
        $businesses = $this->businessRepo->getAll();
        if(Auth::user()->business):
            $business_id = Auth::user()->business->id;
        elseif(Auth::user()->userBusiness):
            $business_id = Auth::user()->userBusiness->id;
        else:
            $business_id = null;
        endif;
        $branches     = $business_id !==null ? $this->branchRepo->getAll($business_id):[];
        $designations = $this->designationRepo->getActiveAll();
        $departments  = $this->departmentRepo->getActiveAll();
        return view('backend.user.create',compact('roles','businesses','branches','designations','departments'));
    }
    public function UserBusinessBranchFetch(Request $request){
        if($request->ajax()):
            $branches     = $this->branchRepo->getAll($request->business_id);
            return view('backend.user.branch_options',compact('branches'));
        endif;
        return '';
    }
    //new user store
    public function store(StoreRequest $request){ 
        if(!isSuperadmin()): 
            if(!$this->repo->checkUserCount()): 
                Toastr::error(__('user_limit_error'),__('errors'));
                return redirect()->route('business.subscription.index');//subscription page
            endif; 
        endif;
        if(env('DEMO')) {
            Toastr::error(__('Store system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->store($request)):
            Toastr::success(__('user_added'),__('success'));
            return redirect()->route('user.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }
    public function edit($id){
        $user      = $this->repo->edit($id);
        $roles     = $this->role->all();
        $businesses = $this->businessRepo->getAll();

        if(Auth::user()->business):
            $business_id = Auth::user()->business->id;
        elseif(Auth::user()->userBusiness):
            $business_id = Auth::user()->userBusiness->id;
        else:
            $business_id = null;
        endif;
        $branches   = $business_id !==null ? $this->branchRepo->getAll($business_id):[];
        $designations = $this->designationRepo->getActiveAll();
        $departments  = $this->departmentRepo->getActiveAll();
        return view('backend.user.edit', compact('user','roles','businesses','branches','designations','departments'));
    }

    //update user
    public function update(UpdateRequest $request){

        if(env('DEMO')) {
            Toastr::error(__('Update system is disable for the demo mode.'),__('errors'));
            return redirect()->back()->withInput();
        }

        if($this->repo->update($request)):
            Toastr::success(__('user_updated'),__('success'));
            return redirect()->route('user.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }

    //delete user
    public function delete($id){
        if(env('DEMO')) {
            Toastr::error(__('Delete system is disable for the demo mode.'),__('errors'));
            return redirect()->back();
        }
        if($id == 1):
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        endif;
        if($this->repo->delete($id)){
            Toastr::success(__('user_deleted'),__('success'));
            return redirect()->route('user.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    //edit user permissions
    public function permissions($id){
        $user         = $this->repo->edit($id);
        $permissions  = $this->role->permissions();
        return view('backend.user.permissions',compact('user','permissions'));
    }

    //update user permissions
    public function permissionsUpdate(Request $request){
        if($this->repo->permissionsUpdate($request)):
            Toastr::success(__('user_permission_updated'),__('success'));
            return redirect()->route('user.index');
        else:
            Toastr::error(__('error'),__('errors'));
            return redirect()->back()->withInput();
        endif;
    }

    //user status change active or inactive
    public function StatusChange($id){
        if($this->repo->statusUpdate($id)){
            return true;
        }else{
            return false;
        }
    }

    //user can ban or unban
    public function BanUnban($id){
        if($this->repo->BanUnban($id)){
            return true;
        }else{
            return false;
        }
    }

    public function view(Request $request,$id){
        $user               = $this->repo->edit($id); 
        $totalTodo          = $this->todolistRepo->totalTodo($user->id); 
        $todoLists          = $this->todolistRepo->userAllTodo($user->id);
        return view('backend.user.view',compact('user','totalTodo','todoLists','request'));
    }


    public function getUserTodoList($id){ 
        $todoLists          = $this->todolistRepo->userAllTodo($id);
        return DataTables::of($todoLists)
        ->addIndexColumn()
        ->editColumn('title',function($todoList){
            return '<a class="text-primary" href="'.route('todoList.view',$todoList->todo_list_id).'">'.@$todoList->todo->title .'</a>';
        })
        ->editColumn('project',function($todoList){
            return '<a class="text-primary" href="'.route('todoList.view',[$todoList,'project'=>'project']).'">'.@$todoList->todo->project->title .'</a>';
        }) 
        ->editColumn('file',function($todoList){
            return '<a href="'.$todoList->todo->file .'" download="" >'. __('download') .'</a>';
        })
        ->editColumn('date',function($todoList){
            return @dateFormat($todoList->todo->date);
        })
        ->editColumn('status',function($todoList){
            return $todoList->mystatus;
        })
        ->editColumn('status_update',function($todoList)use($id){
            if(hasPermission('todo_statusupdate')):
                $statusUpdate ='';
                if($todoList->status == TodoStatus::COMPLETED):
                    $statusUpdate .= '<i class="fa fa-ellipsis"></i>';
                else:
                    $statusUpdate .= '<div class="dropdown ">';
                    $statusUpdate .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $statusUpdate .= '<i class="fa fa-cogs"></i>';
                    $statusUpdate .= '</a>';
                    $statusUpdate .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    $statusUpdate .=  '<a class="dropdown-item" href="'.route('todoList.status.update',[$todoList->todo_list_id,'user_id'=>$id]).'">';
                        if($todoList->status == \App\Enums\TodoStatus::PENDING):
                            $statusUpdate .=  __('processing');
                        elseif($todoList->status == \App\Enums\TodoStatus::PROCESSING):
                            $statusUpdate .=  __('completed') ;
                        endif;
                    $statusUpdate .= '</a>';
                    $statusUpdate .= '</div>';
                    $statusUpdate .= '</div>';
                endif; 
                return $statusUpdate;
            else:
                return '...';
            endif;
        }) 
        ->addColumn('action',function($todoList)use($id){
            $action   = '';
            $action .=' <div class="dropdown">';
            $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $action .= '<i class="fa fa-cogs"></i>';
            $action .= '</a>';
            $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
            $action .= '<a href="'.route('todoList.view',$todoList->todo_list_id) .'" class="dropdown-item  "   ><i class="fas fa-eye"></i>'.__('view').'</a>';
                    if(hasPermission('todo_delete')):
                        $action .= '<form action="'.route('todoList.delete',[$todoList->todo_list_id,'user_id'=>$id]) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .'data-cancel="'. __('cancel') .'" data-title="'.__('delete_todo') .'">';
                        $action .= method_field('delete');
                        $action .= csrf_field();
                        $action .= '<button type="submit" class="dropdown-item" >';
                        $action .= '<i class="fas fa-trash-alt"></i> '.__('delete');
                        $action .= '</button>';
                        $action .= '</form>';
                    endif;
            $action .='</div>';
            $action .='</div>';

            return $action;
        })
        ->rawColumns(['title','project','file','date','status','status_update','action'])
        ->make(true);
    }

    public function attendanceTotal(Request $request){
        if($request->ajax()):  
            return  $this->attendanceReportRepo->getReport($request);  
        endif;
        return '';
    }

}
