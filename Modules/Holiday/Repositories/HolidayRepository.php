<?php
namespace Modules\Holiday\Repositories;

use App\Enums\Status;
use App\Repositories\Upload\UploadInterface;
use Carbon\Carbon;
use Modules\Holiday\Entities\Holiday;

class HolidayRepository implements HolidayInterface{
    protected $holidayModel,$uploadRepo;
    public function __construct(Holiday $holidayModel,UploadInterface $uploadRepo)
    {
        $this->holidayModel = $holidayModel;
        $this->uploadRepo = $uploadRepo;
    }
    public function get(){
        return $this->holidayModel::orderByDesc('id')->paginate(10);
    }
    public function getAllHoliday(){
        return $this->holidayModel::orderByDesc('id')->get();
    }
    public function getActiveAll(){
        return $this->holidayModel::where('status',Status::ACTIVE)->orderByDesc('id')->paginate(10);
    }
    public function getFind($id){
        return $this->holidayModel::find($id);
    }
    public function store($request){
        try {
            $holiday         = new $this->holidayModel();
            $holiday->name   = $request->name;
            $holiday->from   = Carbon::parse($request->from_date)->format('Y-m-d');
            $holiday->to     = Carbon::parse($request->to_date)->format('Y-m-d');
            $holiday->file   = $this->uploadRepo->upload('holiday','',$request->file);
            $holiday->status = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $holiday->note   = $request->note;
            $holiday->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function update($id,$request){
        try {
            $holiday         = $this->holidayModel::find($id);
            $holiday->name   = $request->name;
            $holiday->from   =  Carbon::parse($request->from_date)->format('Y-m-d');
            $holiday->to     =  Carbon::parse($request->to_date)->format('Y-m-d');
            if($request->file):
            $holiday->file   = $this->uploadRepo->upload('holiday',$holiday->file,$request->file);
            endif;
            $holiday->status = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $holiday->note   = $request->note;
            $holiday->save();
            return true;
        } catch (\Throwable $th) {
           return false;
        }
    }
    public function delete($id){
        try {
            $holiday   = $this->holidayModel->find($id);
            $this->uploadRepo->unlinkImage($holiday->file);
            $holiday->delete();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function statusUpdate($id){
        try {
            $holiday         = $this->holidayModel->find($id);
            $holiday->status = $holiday->status == Status::ACTIVE? Status::INACTIVE:Status::ACTIVE;
            $holiday->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
