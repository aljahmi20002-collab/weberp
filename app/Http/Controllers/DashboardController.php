<?php

namespace App\Http\Controllers;

use App\Models\Backend\Language;
use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Repositories\AdminDashboard\AdminDashboardInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Repositories\BranchInterface;
use Modules\Business\Entities\Business;
use Modules\Plan\Entities\Plan;
use Modules\Subscription\Entities\Subscription;

class DashboardController extends Controller
{
    public $data = [];
    protected $branchRepo,$adminDashboarRepo;
    public function __construct(BranchInterface $branchRepo,AdminDashboardInterface $adminDashboarRepo)
    {
        $this->data['siteTitle']   = 'Dashboard';
        $this->branchRepo          = $branchRepo;
        $this->adminDashboarRepo  = $adminDashboarRepo;
    }
    public function index()
    { 
        if(isUser()):
            return $this->branchDashboard();
        elseif(business()):
            return $this->businessDashboard();
        else:
            return $this->adminDashboard();
        endif;
    }

    public function adminDashboard(){

        $this->data['total_business']            = Business::orderByDesc('id')->count();
        $this->data['total_roles']               = Role::orderByDesc('id')->count();
        $this->data['total_modules']             = Permission::orderByDesc('id')->count();
        $this->data['total_active_subscription'] = Subscription::orderByDesc('id')->whereDate('start_date','<=',Carbon::today()->format('Y-m-d'))->whereDate('end_date','>=',Carbon::today()->format('Y-m-d'))->count();  
        $this->data['total_language']            = Language::orderByDesc('id')->count();
        $this->data['total_plan']                = Plan::orderByDesc('id')->count();

        $this->data['recent_business']           = Business::orderByDesc('id')->limit(5)->get();
        $this->data['recent_plans']              = Plan::orderByDesc('id')->limit(5)->get();
        $this->data['chart_dates']               = $this->adminDashboarRepo->businessRegisterChart()['dates'];
        $this->data['chart_payment']             = $this->adminDashboarRepo->businessRegisterChart()['business_payment'];
        $this->data['business_count']            = $this->adminDashboarRepo->businessRegisterChart()['business_count']; 
        //end todo charts 
        
        return view('backend.admin_dashboard',$this->data);
    }

    public function businessDashboard(){ 
        $this->data['branches']            = $this->branchRepo->getBranches(business_id());
        $this->data['attendance']                = ReportAttendanceFind(Auth::user()->id,\Carbon\Carbon::today()->format('Y-m-d'));
        return view('backend.business_dashboard',$this->data);
    }

    public function branchDashboard(){
        $this->data['attendance']                = ReportAttendanceFind(Auth::user()->id,\Carbon\Carbon::today()->format('Y-m-d'));
        return view('backend.branch_dashboard',$this->data);
    }

}
