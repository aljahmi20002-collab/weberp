<?php
namespace Modules\Support\Repositories\AdminSupport;

use App\Repositories\Upload\UploadInterface;
use Illuminate\Support\Facades\Auth;
use Modules\Support\Entities\AdminSupport;
use Modules\Support\Entities\AdminSupportChat;
use Modules\Support\Entities\Support; 
use Modules\Support\Repositories\AdminSupport\SupportInterface;

class SupportRepository implements SupportInterface{
    protected $model,$upload;
    public function __construct(AdminSupport $model,UploadInterface $upload)
    {
        $this->model  = $model;
        $this->upload = $upload;
    }
  
    public function get(){
        return $this->model::with(['user','department'])->where(function($query){
            if(business()):
                $query->where('user_id',Auth::user()->id);
            endif;
        })->orderByDesc('id')->get();
    }

    public function getFind($id){
        return $this->model::find($id);
    }

    public function store($request){
        try {
            $support                    = new $this->model();
            if(isSuperadmin()):
                $user_id                = $request->user_id;
            else:
                $user_id                = Auth::user()->id;
            endif;
            $support->user_id           = $user_id;
            $support->service_id        = $request->service_id;
            $support->department_id     = $request->department_id;
            $support->priority          = $request->priority;
            $support->subject           = $request->subject;
            $support->description       = $request->description;   
            if($request->attached_file && $request->attached_file != null) {
                $support->attached_file = $this->upload->upload('admin-support','',$request->attached_file);
            } 
            $support->save();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function update($id,$request)
    {
        try {
            $support                    = $this->getFind($id);
            if(isSuperadmin()):
                $user_id                = $request->user_id;
            else:
                $user_id                = Auth::user()->id;
            endif;
            $support->user_id           = $user_id;
            $support->service_id        = $request->service_id;
            $support->department_id     = $request->department_id;
            $support->priority          = $request->priority;
            $support->subject           = $request->subject;
            $support->description       = $request->description;   
            if($request->attached_file && $request->attached_file != null) {
                $support->attached_file = $this->upload->upload('admin-support',$support->attached_file,$request->attached_file);
            } 
            $support->save();
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }
 
    public function reply($request){
        try {
            $reply                = new AdminSupportChat();
            $reply->support_id    = $request->support_id;
            $reply->message       = $request->message;
            if($request->attached_file && $request->attached_file != null) {
                $reply->attached_file = $this->upload->upload('admin-support','',$request->attached_file);
            }
            $reply->user_id       = Auth::user()->id;
            $reply->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function chats($id){
        return  AdminSupportChat::with(['user','attachedFile'])->where('support_id',$id)->orderByDesc('id')->get();
    }
    
    public function delete($id){
        try {
            $support   = $this->model::find($id);
            $this->upload->unlinkImage($support->attached_file);
            AdminSupport::destroy($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }  

    public function statusUpdate($id,$request){
        try {
            $support         = $this->getFind($id);
            $support->status = $request->status;
            $support->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}