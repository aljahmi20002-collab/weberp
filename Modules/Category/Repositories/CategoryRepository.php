<?php
namespace Modules\Category\Repositories;

use App\Enums\Status;
use App\Repositories\Upload\UploadInterface;
use Modules\Category\Entities\Category; 
class CategoryRepository implements CategoryInterface{
    protected $model,$upload;
    public function __construct(Category $model,UploadInterface $upload)
    {
        $this->model  = $model;
        $this->upload = $upload;
    }
    public function get(){
        return $this->model::with('business')->where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->where('parent_id',null)->orderBy('position','asc')->get();
    }
    
    public function categoryFind($request){
        return $this->model::with('business')->where(function($query)use($request){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
            $query->where('name',$request->category);
        })->where('parent_id',null)->orderBy('position','asc')->first();
    }

    public function getAll(){
       return $this->model::with('business')->where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->orderBy('position','asc')->get();
      
    }

    public function getActive(){
        return $this->model::where(function($query){
            if(!isSuperadmin()):
                $query->where('business_id',business_id());
            endif;
        })->where('parent_id',null)->where('status',Status::ACTIVE)->orderBy('position','asc')->paginate(10);
    }
 
    public function getParentCategory($request){
        return $this->model::where(function($query)use($request){
            if(isSuperadmin()):
                $query->where('business_id',$request->business_id);
            else:
                $query->where('business_id',business_id());
            endif;
        })->where('parent_id',null)->orderBy('position','asc')->get();
    }
 
    public function getFind($id){
        return $this->model::find($id);
    }
    public function store($request){
        try { 
            
            if(isSuperadmin()):
                $business_id   = $request->business_id;
            else:
                $business_id   = business_id();
            endif;
            $category               = new $this->model();
            $category->business_id  = $business_id;
            $category->name         = $request->name;
            $category->description  = $request->description;
            if($request->parent_id && !blank($request->parent_id)):
                $category->parent_id = $request->parent_id;
            endif; 
            $category->image_id      = $this->upload->upload('categories','',$request->image);
            $category->position      = $request->position;
            $category->status        = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $category->save();
            return true;

        } catch (\Throwable $th) {
            return false;
        }
    }
    public function update($id,$request){
        try {
           
            if(isSuperadmin()):
                $business_id   = $request->business_id;
            else:
                $business_id   = business_id();
            endif;
            $category               = $this->getFind($id);
            $category->business_id  = $business_id;
            $category->name         = $request->name;
            $category->description  = $request->description;
            if($request->parent_id && !blank($request->parent_id)):
                $category->parent_id = $request->parent_id;
            else:
                $category->parent_id = null; 
            endif;
            if($request->image):
                $category->image_id      = $this->upload->upload('categories',$category->image_id,$request->image);
            endif;
            $category->position      = $request->position;
            $category->status        = $request->status == 'on'? Status::ACTIVE:Status::INACTIVE;
            $category->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function delete($id){
        $category   = $this->getFind($id);
        $this->upload->unlinkImage($category->image_id);
        return $this->model::destroy($id);
    }
    public function statusUpdate($id){
        try {
            $category         = $this->model::find($id);
            $category->status = $category->status == Status::ACTIVE? Status::INACTIVE:Status::ACTIVE;
            $category->save();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    
    public function getActiveCategory($business_id){
        return $this->model::where(function($query)use($business_id){
            if(isSuperadmin()):
                $query->where('business_id',$business_id);
            else:
                $query->where('business_id',business_id());
            endif;
        })->where('parent_id',null)->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
    }

 
    public function getSubcategory($category_id){
  
        return $this->model::where(function($query)use($category_id){ 
            $query->where('business_id',business_id()); 
            $query->where('parent_id',$category_id);
        })->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
 
    }

    public function subcategory($request){
        try {
            return $this->model::where(function($query)use($request){
                if(isSuperadmin()):
                    $query->where('business_id',$request->business_id);
                else:
                    $query->where('business_id',business_id());
                endif; 
                $query->where('parent_id',$request->category_id);
            })->where('status',Status::ACTIVE)->orderBy('position','asc')->get();
        } catch (\Throwable $th) {
           return '';
        }
    }
}