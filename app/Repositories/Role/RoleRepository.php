<?php
namespace App\Repositories\Role;

use App\Enums\Status;
use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Repositories\Role\RoleInterface;
use Illuminate\Support\Facades\Auth;

class RoleRepository implements RoleInterface
 {
    public function permissions()
    {
     return Permission::all();
    }
    public function all(){
        return Role::where('status',Status::ACTIVE)->where(function($query){
            if(business() || isUser()):
                $query->whereNot('id',1); 
            endif;
        })->get();
    }
    public function get(){
        return Role::orderByDesc('id')->paginate(10);
    }
    public function getAll(){
        return Role::orderByDesc('id')->get();
    }

    //role store
    public function store($request){
        try {
           $role             = new Role();
           $role->name       = $request->name;
           $role->slug       = str_replace(' ','-',strtolower($request->name));
           $role->permissions= $request->permissions ? $request->permissions:[];
           $role->status     = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
           $role->save();
           return true;

        } catch (\Throwable $th) {
           return false;
        }
    }

    public function edit($id){
        return Role::find($id);
    }

    //role update
    public function update($request){
        try {
            $role             = Role::find($request->id);
            $role->name       = $request->name;
            $role->slug       = str_replace(' ','-',strtolower($request->name));
            $role->permissions= $request->permissions ? $request->permissions:[];
            $role->status     = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $role->save();
            return true;

         } catch (\Throwable $th) {
            return false;
         }
    }

    //role delete
    public function delete($id){
        try {
            return Role::destroy($id);
        } catch (\Throwable $th) {
            return false;
        }
    }

 }
