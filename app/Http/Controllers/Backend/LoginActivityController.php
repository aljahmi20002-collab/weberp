<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\LoginActivity\LoginActivityInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LoginActivityController extends Controller
{
    protected $repo;
    public function __construct(LoginActivityInterface $repo)
    {
        $this->repo   = $repo;
    }
    public function index(){
        return view('backend.login_activity.index');
    }
    
    public function getAllLoginActivity(){
        $loginActivities = $this->repo->getLatest();
        return DataTables::of($loginActivities)
        ->addIndexColumn() 
        ->editColumn('user',function($log){
        return @$log->user->name;
        })
        ->editColumn('activity',function($log){
            return __(@$log->activity);
        })
        ->editColumn('ip',function($log){
            return @$log->ip;
        })
        ->editColumn('browser',function($log){
            return @$log->browser;
        })
        ->editColumn('os',function($log){
            return @$log->os;
        })
        ->editColumn('device',function($log){
            return @$log->device;
        })
        ->editColumn('date_time',function($log){
            return @dateTimeFormat($log->created_at);
        })  
        ->rawColumns(['user', 'activity', 'ip', 'browser', 'os', 'device','date_time'])
        ->make(true);
    }
}
