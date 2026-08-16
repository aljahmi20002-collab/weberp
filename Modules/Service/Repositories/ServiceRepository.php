<?php
namespace Modules\Service\Repositories;

use App\Enums\Status;
use Modules\Service\Entities\Service;
use Modules\Service\Enums\ServiceType;
use Modules\Service\Repositories\ServiceInterface;
class ServiceRepository implements ServiceInterface {
    protected $serviceModel;
    public function __construct(Service $serviceModel)
    {
        $this->serviceModel = $serviceModel;
    }
    public function get(){
        return $this->serviceModel::where(function($query){ 
            if(isSuperadmin()):
                $query->where('service_type',ServiceType::SUPERADMIN);
            else:
                $query->where('business_id',business_id()); 
            endif;
        })->orderByDesc('id')->paginate(10);
    }
    public function getAll(){
        return $this->serviceModel::where(function($query){ 
            $query->where('business_id',business_id()); 
        })->orderByDesc('id')->get();
    }

    public function getAdminSupportService(){
        return $this->serviceModel::where(function($query){ 
            $query->where('service_type',ServiceType::SUPERADMIN); 
        })->orderByDesc('id')->get();
    }
    public function getActive(){
        //
    }
    public function getFind($id){
        return $this->serviceModel::find($id);
    }
    public function store($request){
        try {
            $service                = new $this->serviceModel(); 
            $service->business_id   =  business_id();
            $service->name          = $request->name;
            $service->price         = $request->price;
            $service->description   = $request->description;
            $service->position      = $request->position;
            if(isSuperadmin()):
                $service->service_type  = ServiceType::SUPERADMIN;
            endif;
            $service->status        = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $service->save();
            return true;
        } catch (\Throwable $th) { 
            return false;
        }
    }
    public function update($id,$request){
        try {
             
            $service              = $this->getFind($id);
            $service->business_id =  business_id();
            $service->name        = $request->name;
            $service->price       = $request->price;
            $service->description = $request->description;
            $service->position    = $request->position;
            if(isSuperadmin()):
                $service->service_type  = ServiceType::SUPERADMIN;
            endif;
            $service->status      = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $service->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        return $this->serviceModel::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $service = $this->serviceModel::find($id);
            $service->status = $service->status == Status::ACTIVE? Status::INACTIVE: Status::ACTIVE;
            $service->save();
            return true; 
        } catch (\Throwable $th) {
           return false;
        }
    }
}