<?php

namespace Modules\Holiday\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Holiday\Http\Requests\StoreRequest;
use Modules\Holiday\Repositories\HolidayInterface;
use Yajra\DataTables\DataTables;

class HolidayController extends Controller
{
    protected $repo;
    public function __construct(HolidayInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        return view('holiday::index');
    }
    
    public function getAllHoliday(){
        $holidays    = $this->repo->getAllHoliday();
        return DataTables::of($holidays)
        ->addIndexColumn() 
        ->editColumn('name',function($holiday){
            return  @$holiday->name;
        })
        ->editColumn('from',function($holiday){
            return @dateFormat2($holiday->from);
        })
        ->editColumn('to',function($holiday){
            return @dateFormat2($holiday->to);
        })
        ->editColumn('file',function($holiday){
            return ' <a href="'. @$holiday->file_path .'" download="">'. __('download') .'</a>';
        })
        ->editColumn('note',function($holiday){
            return @$holiday->note;
        })
        ->editColumn('status',function($holiday){
            return @$holiday->my_status;
        })
        ->editColumn('action',function($holiday){
                $action  = '';
                if(hasPermission('holiday_update') || hasPermission('holiday_delete') || hasPermission('holiday_status_update')):
            
                    $action .= '<div class="dropdown">';
                    $action .= '<a href="#" class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $action .= '<i class="fa fa-cogs"></i>';
                    $action .= '</a>';
                    $action .= '<div class="dropdown-menu todo-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(hasPermission('holiday_status_update')):
                            $action .=' <a class="dropdown-item" href="'. route('hrm.attendance.holiday.status.update',$holiday->id) .'">';
                            $action .=   $holiday->status == \App\Enums\Status::ACTIVE? '<i class="fa fa-ban"></i>':'<i class="fa fa-check"></i>';
                            $action .= @statusUpdate($holiday->status);
                            $action .=' </a>';
                        endif;
                        if(hasPermission('holiday_update')):
                            $action .= '<a href="'. route('hrm.attendance.holiday.edit',$holiday->id) .'" class="dropdown-item"   ><i class="fas fa-pen"></i>'. __('edit') .'</a>';
                        endif;
                        if(hasPermission('holiday_delete')):
                            $action .= '<form action="'.route('hrm.attendance.holiday.delete',$holiday->id) .'" method="post" class="delete-form" id="delete" data-yes='. __('yes') .' data-cancel="'. __('cancel') .'" data-title="'. __('delete_holiday') .'">';
                            $action .= method_field('delete');
                            $action .= csrf_field();
                            $action .=' <button type="submit" class="dropdown-item"   >';
                            $action .= '<i class="fas fa-trash-alt"></i>'. __('delete');
                            $action .= '</button>';
                            $action .= '</form>';
                        endif; 
                        $action .='</div>';
                        $action .= '</div> ';
                else:
                    return '<i class="fa fa-ellipsis"></i>';
                endif;

                return $action;
        })
        ->rawColumns(['name', 'from', 'to', 'file', 'note', 'status', 'action'])
        ->make(true);
    }

    public function create()
    {
        return view('holiday::create');
    }


    public function store(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('store_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->store($request)){
            Toastr::success(__('holiday_added_successfully'),__('success'));
            return redirect()->route('hrm.attendance.holiday.index');
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $holiday     = $this->repo->getFind($id);
        return view('holiday::edit',compact('holiday'));
    }

    public function update(StoreRequest $request)
    {
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->update($request->id,$request)){
            Toastr::success(__('holiday_updated_successfully'),__('success'));
            return redirect()->route('hrm.attendance.holiday.index');
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
            Toastr::success(__('holiday_deleted_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }

    public function statusUpdate($id){
        if(env('DEMO')) {
            Toastr::error(__('update_system_is_disable_for_the_demo_mode'),__('errors'));
            return redirect()->back()->withInput();
        }
        if($this->repo->statusUpdate($id)){
            Toastr::success(__('holiday_updated_successfully'),__('success'));
            return redirect()->back();
        }else{
            Toastr::error(__('error'),__('errors'));
            return redirect()->back();
        }
    }
}
