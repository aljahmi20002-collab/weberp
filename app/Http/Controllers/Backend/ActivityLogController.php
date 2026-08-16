<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    public function index(){ 
        
        return view('backend.activity_log.index');
    }

    public function getAllActivityLogs(){
        $logs = Activity::orderBy('id','desc')->get();
        return DataTables::of($logs)
        ->addIndexColumn()
        ->editColumn('log_name',function($log){
            return @$log->log_name;
        })
        ->editColumn('event',function($log){
            return @$log->event; 
        })
        ->editColumn('subject_type',function($log){
            return @$log->subject_type;
        })
        ->editColumn('description',function($log){
            return @$log->description;
        })
        ->editColumn('view',function($log){
            $view = '';
            if ($log->description !== 'deleted'):
                $view .= '<a  href="#" class=" modalBtn" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-modalsize="modal-lg"   data-url="'.route('activity.logs.view',$log->id) .'" data-title="'. __('what_changes') .'" > <i class="fa fa-eye"   data-bs-toggle="tooltip" title="'. __('view') .'" ></i></a>';
            else:
                $view .='Deleted';
            endif;
            return $view;
        })
        ->rawColumns(['log_name', 'event', 'subject_type', 'description','view'])
        ->make(true);
    }

    public function view($id){
        $logDetails   = Activity::find($id);
        return view('backend.activity_log.view',compact('logDetails'));
    }
}
