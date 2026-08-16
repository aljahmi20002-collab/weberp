<?php

namespace Modules\Attendance\Http\Controllers;

use App\Repositories\User\UserInterface;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Http\Requests\StoreRequest;
use Modules\Attendance\Repositories\AttendanceInterface;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    protected $repo,$userRepo;
    public function __construct(AttendanceInterface $repo,UserInterface $userRepo)
    {
        $this->repo     = $repo;
        $this->userRepo = $userRepo;
    }
    public function index(Request $request)
    {
        // dd($this->repo->attendanceData());
        $data = $this->repo->attendanceData();
        $attendances   = $this->repo->get();
        $users         = $this->userRepo->getAttendanceUsers();
        return view('attendance::index',compact('attendances','users','data','request'));
    }


    public function filter(Request $request)
    { 
        $data = $this->repo->attendanceData();
        $attendances   = $this->repo->get();
        $users         = $this->userRepo->getFilterAttendanceUsers($request);
         
        return view('attendance::index',compact('attendances','users','data','request'));
    }

  
     
    public function store(Request $request)
    {
         
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        $attendanceFind   = $this->repo->getFindDateWise($request->employee_id,$request->date); 
        if($attendanceFind):
            Toastr::success(__('attendance_already_added_successfully'),__('success')); 
            return redirect()->route('hrm.attendance.index');
        endif;
        if($this->repo->store($request)){
            Toastr::success(__('attendance_added_successfully'),__('success')); 
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

      
    
    public function update(Request $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('attendance_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }

    }

     
    public function destroy($id)
    {
        if(env('DEMO')) {
            Toastr::error(__('delete_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->delete($id)){
            Toastr::success(__('attendance_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function edit(Request $request){ 
        $attendance   = $this->repo->getFind($request->id); 
        return view('attendance::modal.edit_modal',compact('attendance'));
    }

   
    public function createModal(Request $request){
        $user = $this->userRepo->edit($request->employee_id);
        return view('attendance::modal.create_modal',compact('user','request'));
    }

    public function detailsModal(Request $request){ 
        $attendance   = $this->repo->getFindDateWise($request->employee_id,$request->date); 
        return view('attendance::modal.details_modal',compact('attendance'));
    }
    public function checkoutModal(Request $request){ 
        $attendance   = $this->repo->getFindDateWise($request->employee_id,$request->date); 
        return view('attendance::modal.checkout_modal',compact('attendance'));
    }

}
